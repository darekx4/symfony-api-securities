<?php

namespace App\Service;

use App\Constants\Generic;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class RequestProcessor
{
    /**
     * @throws Exception
     */
    public function deserialize(Request $request, $object): object
    {
        try {
            $encoders = [new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            return $serializer->deserialize($request->getContent(), $object, 'json');
        } catch (NotEncodableValueException $e) {
            throw new Exception(Generic::COULD_NOT_DESERIALIZE_REQUEST);
        }
    }
}
