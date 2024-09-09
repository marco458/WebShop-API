<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\OrderRepository;
use App\State\OrderStateProcessor;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[
    ORM\Table(name: 'orders'),
    ORM\Entity(repositoryClass: OrderRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/orders',
            normalizationContext: [
                'groups' => ['order:get', 'order_modifier:get', 'order_buyer:get', 'product:get'],
            ],
            denormalizationContext: [
                'groups' => ['order:create', 'order_modifier:create', 'order_buyer:create'],
            ],
            name: 'api_v1_orders_create',
            processor: OrderStateProcessor::class,
        ),
        new Get(
            uriTemplate: '/orders/{id}',
            normalizationContext: [
                'groups' => ['order:get', 'order_modifier:get', 'order_buyer:get', 'product:get'],
            ],
            forceEager: false,
            name: 'api_v1_orders_get',
        ),
        new GetCollection(
            uriTemplate: '/orders',
            normalizationContext: [
                'groups' => ['order:get', 'order_modifier:get', 'order_buyer:get', 'product:get'],
            ],
            name: 'api_v1_orders_index'
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class Order implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['order:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(name: 'price_without_modifiers', type: Types::DECIMAL, precision: 12, scale: 4),
        Groups(['order:get']),
    ]
    private float $priceWithoutModifiers;

    #[
        ORM\Column(name: 'final_price', type: Types::DECIMAL, precision: 12, scale: 4),
        Groups(['order:get']),
    ]
    private float $finalPrice;

    #[
        ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE),
        Groups(['order:get']),
    ]
    private \DateTimeInterface $createdAt;

    #[
        ORM\OneToOne(mappedBy: 'madeOrder', cascade: ['persist', 'remove']),
        MaxDepth(1),
        Groups(['order:create', 'order:get']),
    ]
    private ?OrderBuyer $orderBuyer = null;

    #[
        ORM\ManyToMany(targetEntity: Product::class),
        ORM\JoinTable(name: 'orders_products'),
        MaxDepth(1),
        Groups(['order:create', 'order:get']),
        ApiProperty(example: ['/api/v1/products/1', '/api/v1/products/2'])
    ]
    private Collection $products;

    #[
        ORM\OneToMany(mappedBy: 'appliedToOrder', targetEntity: OrderModifiers::class, cascade: ['persist', 'remove']),
        Groups(['order:create', 'order:get']),
    ]
    private Collection $modifiers;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->modifiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceWithoutModifiers(): float
    {
        return $this->priceWithoutModifiers;
    }

    public function setPriceWithoutModifiers(float $priceWithoutModifiers): self
    {
        $this->priceWithoutModifiers = $priceWithoutModifiers;

        return $this;
    }

    public function getFinalPrice(): float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(float $finalPrice): self
    {
        $this->finalPrice = $finalPrice;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOrderBuyer(): ?OrderBuyer
    {
        return $this->orderBuyer;
    }

    public function setOrderBuyer(OrderBuyer $orderBuyer): static
    {
        // set the owning side of the relation if necessary
        if ($orderBuyer->getMadeOrder() !== $this) {
            $orderBuyer->setMadeOrder($this);
        }

        $this->orderBuyer = $orderBuyer;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * @return Collection<int, OrderModifiers>
     */
    public function getModifiers(): Collection
    {
        return $this->modifiers;
    }

    public function addModifier(OrderModifiers $modifier): static
    {
        if (!$this->modifiers->contains($modifier)) {
            $this->modifiers->add($modifier);
            $modifier->setAppliedToOrder($this);
        }

        return $this;
    }

    public function removeModifier(OrderModifiers $modifier): static
    {
        if ($this->modifiers->removeElement($modifier)) {
            // set the owning side to null (unless already changed)
            if ($modifier->getAppliedToOrder() === $this) {
                $modifier->setAppliedToOrder(null);
            }
        }

        return $this;
    }
}
