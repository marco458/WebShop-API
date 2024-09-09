<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Filters\ProductFilters;
use App\Repository\ProductRepository;
use App\State\ProductCollectionStateProvider;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'products'),
    ORM\Entity(repositoryClass: ProductRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/products',
            normalizationContext: [
                'groups' => ['product:get', 'category:get'],
            ],
            denormalizationContext: [
                'groups' => ['product:create'],
            ],
            name: 'api_v1_gases_create',
        ),
        new Get(
            uriTemplate: '/products/{id}',
            normalizationContext: [
                'groups' => ['product:get', 'category:get', 'price_list:get'],
            ],
            name: 'api_v1_gases_get'
        ),
        new GetCollection(
            uriTemplate: '/products',
            openapiContext: [
                'parameters' => ProductFilters::LIST,
            ],
            normalizationContext: [
                'groups' => ['product:get', 'category:get', 'price_list:get'],
            ],
            name: 'api_v1_gases_index',
            provider: ProductCollectionStateProvider::class
        ),
        new Patch(
            uriTemplate: '/products/{id}',
            normalizationContext: [
                'groups' => ['product:get', 'category:get'],
            ],
            denormalizationContext: [
                'groups' => ['product:update'],
            ],
            name: 'api_v1_gases_update'
        ),
        new Delete(
            uriTemplate: '/products/{id}',
            name: 'api_v1_gases_delete',
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    forceEager: false,
    security: 'is_granted("ROLE_ADMIN")',
)]
class Product implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['product:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(name: 'name', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'product.validation.name.please_enter_name', groups: ['product:create', 'product:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'product.validation.name.must_be_at_least_1_character_long',
            maxMessage: 'product.validation.name.must_be_under_255_characters',
            groups: ['product:create', 'product:update']
        ),
        Groups(['product:create', 'product:update', 'product:get'])
    ]
    private string $name;

    #[
        ORM\Column(name: 'description', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'product.validation.description.please_enter_description', groups: ['product:create', 'product:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'product.validation.description.must_be_at_least_1_character_long',
            maxMessage: 'product.validation.description.must_be_under_255_characters',
            groups: ['product:create', 'product:update']
        ),
        Groups(['product:create', 'product:update', 'product:get'])
    ]
    private string $description;

    #[
        ORM\Column(type: Types::DECIMAL, precision: 12, scale: 4),
        Assert\NotNull(
            message: 'product.validation.price.please_provide_price',
            groups: ['product:update', 'product:create']
        ),
        Assert\NotBlank(
            message: 'product.validation.price.please_provide_price',
            groups: ['product:update', 'product:create']
        ),
        Groups(['product:update', 'product:create', 'product:get']),
    ]
    private float $price;

    #[
        ORM\Column(name: 'sku', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'product.validation.sku.please_enter_description', groups: ['product:create', 'product:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'product.validation.sku.must_be_at_least_1_character_long',
            maxMessage: 'product.validation.sku.must_be_under_255_characters',
            groups: ['product:create', 'product:update']
        ),
        Groups(['product:create', 'product:update', 'product:get'])
    ]
    private string $sku;

    #[
        ORM\Column(name: 'published', type: Types::BOOLEAN, options: ['default' => 0]),
        Groups(['product:create', 'product:update', 'product:get'])
    ]
    private bool $published = false;

    #[
        ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'product'),
        Groups(['product:create', 'product:update', 'product:get']),
        MaxDepth(1),
        ApiProperty(example: ['/api/v1/categories/1', '/api/v1/categories/2'])
    ]
    private Collection $categories;

    #[
        ORM\OneToMany(mappedBy: 'product', targetEntity: PriceList::class, orphanRemoval: true),
        MaxDepth(1),
        Groups(['product:get']),
    ]
    private Collection $priceLists;

    #[
        ORM\OneToMany(mappedBy: 'product', targetEntity: ContractList::class),
        MaxDepth(1),
    ]
    private Collection $contractLists;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->priceLists = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceList>
     */
    public function getPriceLists(): Collection
    {
        return $this->priceLists;
    }

    public function addPriceList(PriceList $priceList): static
    {
        if (!$this->priceLists->contains($priceList)) {
            $this->priceLists->add($priceList);
            $priceList->setProduct($this);
        }

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
            $contractList->setProduct($this);
        }

        return $this;
    }

    public function removeContractList(ContractList $contractList): static
    {
        if ($this->contractLists->removeElement($contractList)) {
            // set the owning side to null (unless already changed)
            if ($contractList->getProduct() === $this) {
                $contractList->setProduct(null);
            }
        }

        return $this;
    }
}
