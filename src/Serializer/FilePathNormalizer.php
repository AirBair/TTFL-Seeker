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

    private const string ALREADY_CALLED = 'ATTACHMENT_NORMALIZER_ALREADY_CALLED';

    public function __construct(
        private readonly StorageInterface $storage
    ) {}

    /**
     * @param NbaGame|NbaPlayer|NbaStatsLog|NbaTeam $data
     * @param array<string, mixed>                  $context
     *
     * @throws ExceptionInterface
     */
    #[\Override]
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|\ArrayObject|bool|float|int|string|null
    {
        $context[self::ALREADY_CALLED] = true;

        if ($data instanceof NbaTeam) {
            $data->setLogoFilePath($this->storage->resolveUri($data, 'logoFile'));
        }
        if ($data instanceof NbaPlayer && null !== $data->getNbaTeam()) {
            $data->getNbaTeam()->setLogoFilePath($this->storage->resolveUri($data->getNbaTeam(), 'logoFile'));
        }
        if ($data instanceof NbaStatsLog) {
            $data->getNbaTeam()?->setLogoFilePath($this->storage->resolveUri($data->getNbaTeam(), 'logoFile'));
            $data->getNbaGame()?->getLocalNbaTeam()?->setLogoFilePath($this->storage->resolveUri($data->getNbaGame()?->getLocalNbaTeam(), 'logoFile'));
            $data->getNbaGame()?->getVisitorNbaTeam()?->setLogoFilePath($this->storage->resolveUri($data->getNbaGame()?->getVisitorNbaTeam(), 'logoFile'));
        }
        if ($data instanceof NbaGame) {
            $data->getLocalNbaTeam()?->setLogoFilePath($this->storage->resolveUri($data->getLocalNbaTeam(), 'logoFile'));
            $data->getVisitorNbaTeam()?->setLogoFilePath($this->storage->resolveUri($data->getVisitorNbaTeam(), 'logoFile'));
        }

        return $this->normalizer->normalize($data, $format, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    #[\Override]
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
    #[\Override]
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
