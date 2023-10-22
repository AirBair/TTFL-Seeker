<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Entity\NbaGame;
use App\Entity\NbaPlayer;
use App\Entity\NbaStatsLog;
use App\Entity\NbaTeam;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class FilePathNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    private const ALREADY_CALLED = 'ATTACHMENT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private readonly StorageInterface $storage
    ) {}

    /**
     * @param NbaGame|NbaPlayer|NbaStatsLog|NbaTeam $object
     * @param array<string, mixed>                  $context
     *
     * @throws ExceptionInterface
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): null|array|\ArrayObject|bool|float|int|string
    {
        $context[self::ALREADY_CALLED] = true;

        if ($object instanceof NbaTeam) {
            $object->setLogoFilePath($this->storage->resolveUri($object, 'logoFile'));
        }
        if ($object instanceof NbaPlayer && null !== $object->getNbaTeam()) {
            $object->getNbaTeam()->setLogoFilePath($this->storage->resolveUri($object->getNbaTeam(), 'logoFile'));
        }
        if ($object instanceof NbaStatsLog) {
            $object->getNbaTeam()?->setLogoFilePath($this->storage->resolveUri($object->getNbaTeam(), 'logoFile'));
            $object->getNbaGame()?->getLocalNbaTeam()?->setLogoFilePath($this->storage->resolveUri($object->getNbaGame()?->getLocalNbaTeam(), 'logoFile'));
            $object->getNbaGame()?->getVisitorNbaTeam()?->setLogoFilePath($this->storage->resolveUri($object->getNbaGame()?->getVisitorNbaTeam(), 'logoFile'));
        }
        if ($object instanceof NbaGame) {
            $object->getLocalNbaTeam()?->setLogoFilePath($this->storage->resolveUri($object->getLocalNbaTeam(), 'logoFile'));
            $object->getVisitorNbaTeam()?->setLogoFilePath($this->storage->resolveUri($object->getVisitorNbaTeam(), 'logoFile'));
        }

        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof NbaGame || $data instanceof NbaPlayer || $data instanceof NbaStatsLog || $data instanceof NbaTeam;
    }

    /**
     * @return array<'*'|'object'|class-string|string, null|bool>
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            NbaGame::class => false,
            NbaPlayer::class => false,
            NbaStatsLog::class => false,
            NbaTeam::class => false,
        ];
    }
}
