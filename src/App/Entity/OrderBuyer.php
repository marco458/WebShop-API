<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\OrderBuyerRepository;
use Core\Constant\Constants;
use Core\Entity\EntityInterface;
use Core\Entity\User\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Table(name: 'order_buyers'),
    ORM\Entity(repositoryClass: OrderBuyerRepository::class),
    ORM\HasLifecycleCallbacks()
]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/order-buyers/{id}',
            normalizationContext: [
                'groups' => ['order_buyer:get'],
            ],
            name: 'api_v1_order_buyers_get'
        ),
        new GetCollection(
            uriTemplate: '/order-buyers',
            normalizationContext: [
                'groups' => ['order_buyer:get'],
            ],
            name: 'api_v1_order_buyers_index'
        ),
    ],
    routePrefix: '/'.Constants::API_VERSION_V1,
    security: 'is_granted("ROLE_ADMIN")',
)]
class OrderBuyer implements EntityInterface
{
    #[
        ORM\Column(name: 'id', type: Types::INTEGER, nullable: false),
        ORM\GeneratedValue(strategy: 'IDENTITY'),
        ORM\Id,
        Groups(['order_buyer:get']),
    ]
    private ?int $id = null;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Assert\NotNull(message: 'order_buyer.validation.first_name.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.first_name.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $firstName;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Assert\NotNull(message: 'order_buyer.validation.last_name.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.last_name.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $lastName;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Assert\NotNull(message: 'order_buyer.validation.email.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.email.required', groups: ['order_buyer:create']),
        Assert\Email(message: 'order_buyer.validation.email.invalid', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $email;

    #[
        ORM\Column(type: Types::STRING, length: 20),
        Assert\NotNull(message: 'order_buyer.validation.phone_number.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.phone_number.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $phoneNumber;

    #[
        ORM\Column(type: Types::STRING, length: 255),
        Assert\NotNull(message: 'order_buyer.validation.address.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.address.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $address;

    #[
        ORM\Column(type: Types::STRING, length: 100),
        Assert\NotNull(message: 'order_buyer.validation.city.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.city.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $city;

    #[
        ORM\Column(type: Types::STRING, length: 100),
        Assert\NotNull(message: 'order_buyer.validation.country.required', groups: ['order_buyer:create']),
        Assert\NotBlank(message: 'order_buyer.validation.country.required', groups: ['order_buyer:create']),
        Groups(['order_buyer:create', 'order_buyer:get'])
    ]
    private string $country;

    #[
        ORM\ManyToOne,
        ORM\JoinColumn(nullable: true),
        MaxDepth(1),
        Groups(['order_buyer:get']),
    ]
    private ?User $originalUser = null;

    #[
        ORM\OneToOne(inversedBy: 'orderBuyer', cascade: ['persist', 'remove']),
        ORM\JoinColumn(nullable: false),
        MaxDepth(1),
        Groups(['order_buyer:get']),
    ]
    private ?Order $madeOrder = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getOriginalUser(): ?User
    {
        return $this->originalUser;
    }

    public function setOriginalUser(?User $originalUser): static
    {
        $this->originalUser = $originalUser;

        return $this;
    }

    public function getMadeOrder(): ?Order
    {
        return $this->madeOrder;
    }

    public function setMadeOrder(?Order $madeOrder): static
    {
        $this->madeOrder = $madeOrder;

        return $this;
    }
}
