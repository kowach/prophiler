<?php
/**
 * @author Fabian Fuelling <fabian@fabfuel.de>
 * @created: 17.11.14 14:37
 */

namespace Fabfuel\Prophiler;

class ToolbarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Fabfuel\Prophiler\Toolbar::__construct
     * @covers Fabfuel\Prophiler\Toolbar::setProfiler
     * @covers Fabfuel\Prophiler\Toolbar::getProfiler
     */
    public function testGetAndSetProfiler()
    {
        $profiler = $this->getMockBuilder('Fabfuel\Prophiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $toolbar = new Toolbar($profiler);

        $this->assertSame($profiler, $toolbar->getProfiler());
    }

    /**
     * @covers Fabfuel\Prophiler\Toolbar::__construct
     * @covers Fabfuel\Prophiler\Toolbar::render
     * @uses Fabfuel\Prophiler\Toolbar
     * @uses Fabfuel\Prophiler\Iterator\ComponentFilteredIterator
     * @uses Fabfuel\Prophiler\Toolbar\Formatter\TimelineFormatter
     */
    public function testRender()
    {
        $profiler = $this->getMockBuilder('Fabfuel\Prophiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $profiler->expects($this->exactly(2))
            ->method('getDuration')
            ->willReturn(100);

        $toolbar = new Toolbar($profiler);

        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')->getMock();
        $toolbar->dispatcher = $dispatcher;

        $output = $toolbar->render();

        $this->assertNotEmpty($output);
    }

    public function testPartial()
    {
        $profiler = $this->getMockBuilder('Fabfuel\Prophiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $toolbar = new Toolbar($profiler);

        ob_start();
        $toolbar->partial('../../../../tests/Fabfuel/Prophiler/View/test', ['foobar' => 'ipsum']);
        $output = ob_get_clean();

        $this->assertSame('<lorem>ipsum</lorem>
', $output);
    }

    /**
     * @covers Fabfuel\Prophiler\Toolbar::addDataCollector
     * @covers Fabfuel\Prophiler\Toolbar::getDataCollectors
     * @uses Fabfuel\Prophiler\Toolbar
     */
    public function testAddAndGetDataCollectors()
    {
        $profiler = $this->getMockBuilder('Fabfuel\Prophiler\Profiler')
            ->disableOriginalConstructor()
            ->getMock();

        $toolbar = new Toolbar($profiler);

        $this->assertCount(0, $toolbar->getDataCollectors());

        $dataCollector = $this->getMock('Fabfuel\Prophiler\DataCollector\Request');
        $toolbar->addDataCollector($dataCollector);

        $this->assertCount(1, $toolbar->getDataCollectors());
        $this->assertSame($dataCollector, $toolbar->getDataCollectors()[0]);
    }
}