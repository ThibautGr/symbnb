<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today", message="La date d'arriver dois être ulterieure à aurjoud'hui", groups={"front"})
     * @Assert\Type("DateTimeInterface",message="Attention, la date d'arrivée doit être au bon format !")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="startDate", message="la date de départ dois êtr superieure à celle de départ", groups={"front"})
     * @Assert\Type("DateTimeInterface", message="Attention, la date d'arrivée doit être au bon format !")
     */
    private $endTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private  $comment;

    /**
     * Callback
     * @ORM\PrePersist
     *
     * @return void
     */
    public function prePersist(){
        if(empty($this->createAt)){
            $this->createAt = new \DateTime();
        }

        if (empty($this->amount)){
            //prix  x * nombre de jour
            dump($this->ad);
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates(){
        // connaitre les dates réservers
            $notAvailableDays = $this->ad->getNotAvalibleDate();
        //comparer les dates choisie avec les dates impossibles
        $bookingDayes = $this->getDays();

        $formatDay = function ($day){
            return $day->format('Y-m-d');
        };

        // deux array pour une conversion en chaine de caracteres
        $days = array_map($formatDay, $bookingDayes);

        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day ){
            if(array_search($day, $notAvailable) !== false) return false;
        }
        return true;

    }

    /**
     * Retourne un tableau des journées de réservation
     *
     * @return array // dateTime
     */
    public function getDays(): array
    {
        $result = range (
            $this->startDate->getTimestamp(),
            $this->endTime->getTimestamp(),
            24 * 60 * 60
        );

        $days = array_map(function ($dayTimeStanp){
            return new \DateTime(date('Y-m-d', $dayTimeStanp));
        }, $result);

        return $days;
    }

    public function getDuration(){
        $diff = $this->endTime->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
