<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
namespace Phanbook\Common\Library\Providers;

use Phalcon\Cli\Dispatcher as CliDi;
use Phanbook\Common\Library\Events\AccessListener;
use Phanbook\Common\Library\Mvc\Dispatcher as MvcDi;
use Phanbook\Common\Library\Events\DispatcherListener;

/**
 * \Phanbook\Common\Library\Providers\MvcDispatcherServiceProvider
 *
 * @package Phanbook\Common\Library\Providers
 */
class MvcDispatcherServiceProvider extends AbstractServiceProvider
{
    /**
     * The Service name.
     * @var string
     */
    protected $serviceName = 'dispatcher';

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function register()
    {
        $this->di->setShared(
            $this->serviceName,
            function () {
                if (container('bootstrap')->getMode() == 'cli') {
                    $dispatcher = new CliDi();
                } else {
                    $dispatcher = new MvcDi();
                }

                container('eventsManager')->attach('dispatch', new DispatcherListener($this));
                container('eventsManager')->attach('dispatch:beforeDispatch', new AccessListener($this));

                $dispatcher->setDI(container());
                $dispatcher->setEventsManager(container('eventsManager'));

                return $dispatcher;
            }
        );
    }
}
