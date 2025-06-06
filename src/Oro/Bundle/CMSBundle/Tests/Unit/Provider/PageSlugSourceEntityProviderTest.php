<?php

namespace Oro\Bundle\CMSBundle\Tests\Unit\Provider;

use Oro\Bundle\CMSBundle\Entity\Page;
use Oro\Bundle\CMSBundle\Provider\PageSlugSourceEntityProvider;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;
use Oro\Bundle\RedirectBundle\DependencyInjection\Configuration;
use Oro\Bundle\RedirectBundle\Entity\Slug;
use Oro\Bundle\RedirectBundle\Provider\SluggableEntityFinder;

class PageSlugSourceEntityProviderTest extends \PHPUnit\Framework\TestCase
{
    /** @var SluggableEntityFinder|\PHPUnit\Framework\MockObject\MockObject */
    private $sluggableEntityFinder;

    /** @var ConfigManager|\PHPUnit\Framework\MockObject\MockObject */
    private $configManager;

    /** @var PageSlugSourceEntityProvider */
    private $provider;

    #[\Override]
    protected function setUp(): void
    {
        $this->sluggableEntityFinder = $this->createMock(SluggableEntityFinder::class);
        $this->configManager = $this->createMock(ConfigManager::class);

        $this->provider = new PageSlugSourceEntityProvider(
            $this->sluggableEntityFinder,
            $this->configManager
        );
    }

    public function testGetSourceEntityBySlugWhenConfigIsOff()
    {
        $slug = new Slug();
        $this->configManager->expects($this->once())
            ->method('get')
            ->with(Configuration::getConfigKey(Configuration::ENABLE_DIRECT_URL))
            ->willReturn(false);
        $this->sluggableEntityFinder->expects($this->never())
            ->method('findEntityBySlug');
        $this->assertNull($this->provider->getSourceEntityBySlug($slug));
    }

    public function testGetSourceEntityBySlug()
    {
        $this->configManager->expects($this->once())
            ->method('get')
            ->with(Configuration::getConfigKey(Configuration::ENABLE_DIRECT_URL))
            ->willReturn(true);
        $slug = new Slug();
        $sourceEntity = new Page();
        $this->sluggableEntityFinder->expects($this->once())
            ->method('findEntityBySlug')
            ->with(Page::class, $this->identicalTo($slug))
            ->willReturn($sourceEntity);
        $this->assertSame($sourceEntity, $this->provider->getSourceEntityBySlug($slug));
    }
}
