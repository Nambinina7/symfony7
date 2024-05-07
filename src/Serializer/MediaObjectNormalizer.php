<?php

namespace App\Serializer;

use App\Entity\About;
use App\Entity\Banner;
use App\Entity\BannerItems;
use App\Entity\Technology;
use App\Entity\User;
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

        if ($object instanceof User) {
            $prefixUser = User::PATH_USER;
            $object->path = $prefixUser . $object->image;
        }

        if ($object instanceof Technology) {
            $prefixTechnology = Technology::PATH_TECHNOLOGY;
            $object->path = $prefixTechnology . $object->image;
        }

        if ($object instanceof About) {
            $prefixAbout = About::PATH_ABOUT;
            $object->path = $prefixAbout . $object->image;
        }

        if ($object instanceof Banner) {
            $isMobileRequest = $request && $request->attributes->get('_route') === Banner::ROUTE_MOBILE;
            $prefix = $isMobileRequest ? BannerItems::PATH_MOBILE : BannerItems::PATH_WEB;
            foreach($object->getBannerItems() as $bannerItem) {
                $bannerItem->path = $prefix . $bannerItem->image;
            }
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {

        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof Banner || $data instanceof User || $data instanceof Technology || $data instanceof About;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Banner::class => true,
            User::class => true,
            Technology::class => true,
            About::class => true,
        ];
    }
}
