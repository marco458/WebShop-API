<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\OrderModifiersRepository;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'order_modifiers'),
    ORM\Entity(repositoryClass: OrderModifiersRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/order-modifiers/{id}',
            normalizationContext: [
                'groups' => ['order_modifier:get'],
            ],
            name: 'api_v1_order_modifiers_get'
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class OrderModifiers implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['order_modifier:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Assert\NotBlank(message: 'order_modifier.validation.name.required', groups: ['order_modifier:create']),
        Assert\NotNull(message: 'order_modifier.validation.name.required', groups: ['order_modifier:create']),
        Groups(['order_modifier:create', 'order_modifier:get'])
    ]
    private string $name;

    #[
        ORM\Column(name: 'percentage', type: Types::FLOAT, precision: 12, scale: 4),
        Assert\NotNull(message: 'order_modifier.validation.discount_percentage.required', groups: ['order_modifier:create']),
        Groups(['order_modifier:create', 'order_modifier:get'])
    ]
    private float $percentage;

    #[
        ORM\Column(name: 'discount', type: Types::BOOLEAN, options: ['default' => 0]),
        Groups(['order_modifier:create', 'order_modifier:get'])
    ]
    private bool $discount = false;

    #[
        ORM\Column(type: Types::FLOAT, nullable: true),
        Assert\GreaterThanOrEqual(0, groups: ['order_modifier:create']),
        Groups(['order_modifier:create', 'order_modifier:get'])
    ]
    private ?float $activateIfPriceGreaterThan = null;

    #[
        ORM\ManyToOne(inversedBy: 'modifiers'),
        ORM\JoinColumn(nullable: false),
        MaxDepth(1),
        Groups(['order_modifier:get']),
    ]
    private ?Order $appliedToOrder = null;

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

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function isDiscount(): bool
    {
        return $this->discount;
    }

    public function setDiscount(bool $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getActivateIfPriceGreaterThan(): ?float
    {
        return $this->activateIfPriceGreaterThan;
    }

    public function priceActivationThreshold(): ?float
    {
        return $this->activateIfPriceGreaterThan;
    }

    public function setActivateIfPriceGreaterThan(?float $activateIfPriceGreaterThan): self
    {
        $this->activateIfPriceGreaterThan = $activateIfPriceGreaterThan;

        return $this;
    }

    public function getAppliedToOrder(): ?Order
    {
        return $this->appliedToOrder;
    }

    public function setAppliedToOrder(?Order $appliedToOrder): static
    {
        $this->appliedToOrder = $appliedToOrder;

        return $this;
    }
}
