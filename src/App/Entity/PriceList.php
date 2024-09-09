<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Filters\BaseFilters;
use App\Repository\PriceListRepository;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'price_lists'),
    ORM\Entity(repositoryClass: PriceListRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/price-lists',
            normalizationContext: [
                'groups' => ['price_list:get'],
            ],
            denormalizationContext: [
                'groups' => ['price_list:create'],
            ],
            name: 'api_v1_price_lists_create',
        ),
        new Get(
            uriTemplate: '/price-lists/{id}',
            normalizationContext: [
                'groups' => ['price_list:get'],
            ],
            name: 'api_v1_price_lists_get'
        ),
        new GetCollection(
            uriTemplate: '/price-lists',
            openapiContext: [
                'parameters' => BaseFilters::LIST,
            ],
            normalizationContext: [
                'groups' => ['price_list:get'],
            ],
            name: 'api_v1_price_lists_index',
        ),
        new Patch(
            uriTemplate: '/price-lists/{id}',
            normalizationContext: [
                'groups' => ['price_list:get'],
            ],
            denormalizationContext: [
                'groups' => ['price_list:update'],
            ],
            name: 'api_v1_price_lists_update'
        ),
        new Delete(
            uriTemplate: '/price-lists/{id}',
            name: 'api_v1_price_lists_delete',
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class PriceList implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['price_list:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(name: 'name', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'price_list.validation.name.please_enter_name', groups: ['price_list:create', 'price_list:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'price_list.validation.name.must_be_at_least_1_character_long',
            maxMessage: 'price_list.validation.name.must_be_under_255_characters',
            groups: ['price_list:create', 'price_list:update']
        ),
        Groups(['price_list:create', 'price_list:update', 'price_list:get'])
    ]
    private string $name;

    #[
        ORM\Column(type: Types::DECIMAL, precision: 12, scale: 4),
        Assert\NotNull(
            message: 'price_list.validation.price.please_provide_price',
            groups: ['price_list:update', 'price_list:create']
        ),
        Assert\NotBlank(
            message: 'price_list.validation.price.please_provide_price',
            groups: ['price_list:update', 'price_list:create']
        ),
        Groups(['price_list:update', 'price_list:create', 'price_list:get']),
    ]
    private float $price;

    #[
        ORM\Column(name: 'sku', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'price_list.validation.sku.please_enter_description', groups: ['price_list:create', 'price_list:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'price_list.validation.sku.must_be_at_least_1_character_long',
            maxMessage: 'price_list.validation.sku.must_be_under_255_characters',
            groups: ['price_list:create', 'price_list:update']
        ),
        Groups(['price_list:create', 'price_list:update', 'price_list:get'])
    ]
    private string $sku;

    #[
        ORM\ManyToOne(inversedBy: 'priceLists'),
        ORM\JoinColumn(nullable: false),
        Groups(['price_list:create', 'price_list:update', 'price_list:get']),
        ApiProperty(example: '/api/v1/products/1')
    ]
    private Product $product;

    #[ORM\OneToMany(mappedBy: 'priceList', targetEntity: ContractList::class)]
    private Collection $contractLists;

    public function __construct()
    {
        $this->contractLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): self
    {
        $this->sku = $sku;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection<int, ContractList>
     */
    public function getContractLists(): Collection
    {
        return $this->contractLists;
    }

    public function addContractList(ContractList $contractList): static
    {
        if (!$this->contractLists->contains($contractList)) {
            $this->contractLists->add($contractList);
            $contractList->setPriceList($this);
        }

        return $this;
    }

    public function removeContractList(ContractList $contractList): static
    {
        if ($this->contractLists->removeElement($contractList)) {
            // set the owning side to null (unless already changed)
            if ($contractList->getPriceList() === $this) {
                $contractList->setPriceList(null);
            }
        }

        return $this;
    }
}
