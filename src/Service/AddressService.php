<?php

namespace App\Service;

use App\Entity\AddressBook;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AddressService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileService
     */
    private $fileService;

    /**
     * AddressService constructor.
     * @param EntityManagerInterface $em
     * @param FileService $fileService
     */
    public function __construct(EntityManagerInterface $em, FileService $fileService)
    {
        $this->em = $em;
        $this->fileService = $fileService;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return int|null
     */
    public function insertUpdate(Request $request, int $id = 0)
    {
        $previousFile = null;
        if ($id > 0) {
            $addressBook = $this->em->getRepository(AddressBook::class)->find($id);
            $previousFile = $addressBook->getPicture();
        } else {
            $addressBook = new AddressBook();
        }
        $addressBook->setFirstName($request->get('firstName'));
        $addressBook->setLastName($request->get('lastName'));
        $addressBook->setBirthDay($request->get('birthday'));
        $addressBook->setCity($request->get('city'));
        $addressBook->setCountry($request->get('country'));
        $addressBook->setEmail($request->get('email'));
        $addressBook->setPhoneNumber($request->get('phoneNumber'));
        $addressBook->setStreetNumber($request->get('streetNumber'));
        $addressBook->setZip($request->get('zip'));
        $picture = $request->files->get('avatar');
        if ($picture !== null) {
            $filename = $this->fileService->upload($picture);
            if ($filename !== null) {
                $addressBook->setPicture($filename);
            }
            if ($previousFile !== null) {
                $this->fileService->remove($previousFile);
            }
        }
        $this->em->persist($addressBook);
        $this->em->flush();

        // Return last inserted id
        return $addressBook->getId();
    }
}

