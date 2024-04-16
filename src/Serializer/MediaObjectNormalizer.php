<?php

namespace App\Serializer;

use App\Entity\Banner;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MediaObjectNormalizer implements NormalizerInterface
{

    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
        private readonly RequestStack $requestStack,
    ) {
    }

    public function normalize($object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $request = $this->requestStack->getCurrentRequest();
        $isMobileRequest = $request && $request->attributes->get('_route') === Banner::ROUTE_MOBILE;
        $prefix = $isMobileRequest ? Banner::PATH_MOBILE : Banner::PATH_WEB;
        $object->path = $prefix . $object->image;;

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {

        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Banner;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Banner::class => true,
        ];
    }
}