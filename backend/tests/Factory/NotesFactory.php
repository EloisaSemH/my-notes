<?php

namespace App\Tests\Factory;

use App\Entity\Notes;
use Symfony\Component\Uid\Uuid;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Notes>
 */
final class NotesFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Notes::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'content' => self::faker()->text(),
            'created_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'title' => self::faker()->text(255),
            'updated_at' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'uuid' => Uuid::v4()->toRfc4122(),
            'is_pinned' => self::faker()->boolean(),
            'is_archived' => self::faker()->boolean(),
            'source' => self::faker()->randomElement(['php', 'python']),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Notes $notes): void {})
        ;
    }
}
