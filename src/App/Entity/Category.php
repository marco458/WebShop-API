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
use App\Repository\CategoryRepository;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'categories'),
    ORM\Entity(repositoryClass: CategoryRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/categories',
            normalizationContext: [
                'groups' => ['category:get'],
            ],
            denormalizationContext: [
                'groups' => ['category:create'],
            ],
            name: 'api_v1_categories_create',
        ),
        new Get(
            uriTemplate: '/categories/{id}',
            normalizationContext: [
                'groups' => ['category:get'],
            ],
            name: 'api_v1_categories_get'
        ),
        new GetCollection(
            uriTemplate: '/categories',
            openapiContext: [
                'parameters' => BaseFilters::LIST,
            ],
            normalizationContext: [
                'groups' => ['category:get'],
            ],
            name: 'api_v1_categories_index',
        ),
        new Patch(
            uriTemplate: '/categories/{id}',
            normalizationContext: [
                'groups' => ['category:get'],
            ],
            denormalizationContext: [
                'groups' => ['category:update'],
            ],
            name: 'api_v1_categories_update'
        ),
        new Delete(
            uriTemplate: '/categories/{id}',
            name: 'api_v1_categories_delete',
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class Category implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['category:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(name: 'name', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'category.validation.name.please_enter_name', groups: ['category:create', 'category:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'category.validation.name.must_be_at_least_1_character_long',
            maxMessage: 'category.validation.name.must_be_under_255_characters',
            groups: ['category:create', 'category:update']
        ),
        Groups(['category:create', 'category:update', 'category:get'])
    ]
    private string $name;

    #[
        ORM\Column(name: 'description', type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'category.validation.description.please_enter_description', groups: ['category:create', 'category:update']),
        Assert\Length(
            min: 1,
            max: 255,
            minMessage: 'category.validation.description.must_be_at_least_1_character_long',
            maxMessage: 'category.validation.description.must_be_under_255_characters',
            groups: ['category:create', 'category:update']
        ),
        Groups(['category:create', 'category:update', 'category:get'])
    ]
    private string $description;

    #[
        ORM\ManyToMany(targetEntity: Product::class, inversedBy: 'categories'),
        ORM\JoinTable(name: 'categories_products')
    ]
    private Collection $product;

    #[
        ORM\ManyToOne(targetEntity: self::class, cascade: ['remove'], inversedBy: 'parent'),
        Groups(['category:create', 'category:update', 'category:get']),
        ApiProperty(example: '/api/v1/categories/1')
    ]
    private ?self $parent = null;

    public function __construct()
    {
        $this->product = new ArrayCollection();
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

    /**
     * @return Collection<int, Product>
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->product->contains($product)) {
            $this->product->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->product->removeElement($product);

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
