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
use App\Repository\ContractListRepository;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Core\Entity\User\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'contract_lists'),
    ORM\Entity(repositoryClass: ContractListRepository::class),
    ORM\HasLifecycleCallbacks(),
    ORM\UniqueConstraint(
        name: 'user_product_unique_constraint',
        columns: ['user_id', 'product_id']
    )
]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/contract-lists',
            normalizationContext: [
                'groups' => ['contract_list:get', 'product:get', 'user:get'],
            ],
            denormalizationContext: [
                'groups' => ['contract_list:create'],
            ],
            name: 'api_v1_contract_lists_create',
        ),
        new Get(
            uriTemplate: '/contract-lists/{id}',
            normalizationContext: [
                'groups' => ['contract_list:get', 'product:get', 'user:get'],
            ],
            name: 'api_v1_contract_lists_get'
        ),
        new GetCollection(
            uriTemplate: '/contract-lists',
            openapiContext: [
                'parameters' => BaseFilters::LIST,
            ],
            normalizationContext: [
                'groups' => ['contract_list:get', 'product:get', 'user:get'],
            ],
            name: 'api_v1_contract_lists_index',
        ),
        new Patch(
            uriTemplate: '/contract-lists/{id}',
            normalizationContext: [
                'groups' => ['contract_list:get'],
            ],
            denormalizationContext: [
                'groups' => ['contract_list:update'],
            ],
            name: 'api_v1_contract_lists_update'
        ),
        new Delete(
            uriTemplate: '/contract-lists/{id}',
            name: 'api_v1_contract_lists_delete',
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class ContractList implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['contract_list:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(type: Types::DECIMAL, precision: 12, scale: 4),
        Assert\NotNull(
            message: 'contract_list.validation.price.please_provide_price',
            groups: ['contract_list:update', 'contract_list:create']
        ),
        Assert\NotBlank(
            message: 'contract_list.validation.price.please_provide_price',
            groups: ['contract_list:update', 'contract_list:create']
        ),
        Groups(['contract_list:update', 'contract_list:create', 'contract_list:get']),
    ]
    private float $price;

    #[
        ORM\Column(name: 'sku', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'contract_list.validation.sku.please_enter_description', groups: ['contract_list:create', 'contract_list:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'contract_list.validation.sku.must_be_at_least_1_character_long',
            maxMessage: 'contract_list.validation.sku.must_be_under_255_characters',
            groups: ['contract_list:create', 'contract_list:update']
        ),
        Groups(['contract_list:create', 'contract_list:update', 'contract_list:get'])
    ]
    private string $sku;

    #[
        ORM\ManyToOne(inversedBy: 'contractLists'),
        ORM\JoinColumn(nullable: false),
        Groups(['contract_list:create', 'contract_list:update', 'contract_list:get']),
        ApiProperty(example: '/api/v1/users/1')
    ]
    private User $user;

    #[
        ORM\ManyToOne(inversedBy: 'contractLists'),
        ORM\JoinColumn(nullable: false),
        Groups(['contract_list:create', 'contract_list:update', 'contract_list:get']),
        ApiProperty(example: '/api/v1/products/1')
    ]
    private ?Product $product = null;

    #[
        ORM\ManyToOne(inversedBy: 'contractLists'),
        Groups(['contract_list:create', 'contract_list:update', 'contract_list:get']),
        ApiProperty(example: '/api/v1/price-lists/1')
    ]
    private ?PriceList $priceList = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getPriceList(): ?PriceList
    {
        return $this->priceList;
    }

    public function setPriceList(?PriceList $priceList): static
    {
        $this->priceList = $priceList;

        return $this;
    }
}
