<?php

namespace App\Entity;

use App\Repository\UpdatePasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePassword
{



    private $oldPwd;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe dois faire au moins 8 caracètre de long")
     */
    private $newPwd;

    public function getOldPwd(): ?string
    {
        return $this->oldPwd;
    }

    public function setOldPwd(string $oldPwd): self
    {
        $this->oldPwd = $oldPwd;

        return $this;
    }

    public function getNewPwd(): ?string
    {
        return $this->newPwd;
    }

    public function setNewPwd(string $newPwd): self
    {
        $this->newPwd = $newPwd;

        return $this;
    }
}
