<?php

namespace App\EntityListener;

use App\Entity\Trick;
use Symfony\Component\String\Slugger\SluggerInterface;


class TrickListener
{
    public function __construct(private SluggerInterface $slugger) {
    }

    public function prePersist(Trick $trick)
    {
        $this->computeSlug($trick);
    }

    public function preUpdate(Trick $trick)
    {
        $this->computeSlug($trick);
    }

    public function computeSlug(Trick $trick)
    {
        $trick->setSlug(
            $this->slugger->slug($trick->getName())->lower()
        );
    }
}
