<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\SystemSettings;
use Doctrine\ORM\EntityManagerInterface;

class SystemSettingsService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->em->getRepository(SystemSettings::class)->findOneBy(['keyName' => $key]);
        if (!$setting) {
            return $default;
        }

        $decoded = json_decode($setting->getValue(), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $setting->getValue();
    }

    public function set(string $key, mixed $value): void
    {
        $setting = $this->em->getRepository(SystemSettings::class)->findOneBy(['keyName' => $key]);
        if (!$setting) {
            $setting = new SystemSettings();
            $setting->setKeyName($key);
        }

        $setting->setValue($this->encodeValue($value));
        $setting->setUpdatedAt(new \DateTime());
        $this->em->persist($setting);
        $this->em->flush();
    }

    public function ensure(string $key, mixed $value): void
    {
        $existing = $this->em->getRepository(SystemSettings::class)->findOneBy(['keyName' => $key]);
        if ($existing) {
            return;
        }

        $setting = new SystemSettings();
        $setting->setKeyName($key);
        $setting->setValue($this->encodeValue($value));
        $setting->setUpdatedAt(new \DateTime());
        $this->em->persist($setting);
        $this->em->flush();
    }

    private function encodeValue(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return json_encode($value, JSON_UNESCAPED_SLASHES);
    }
}
