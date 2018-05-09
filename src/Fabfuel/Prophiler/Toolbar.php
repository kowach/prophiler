<?php
/**
 * @author @fabfuel <fabian@fabfuel.de>
 * @created 16.11.14, 16:34
 */
namespace Fabfuel\Prophiler;

use Fabfuel\Prophiler\Adapter\Psr\Log\Logger;
use Fabfuel\Prophiler\Iterator\ComponentFilteredIterator;

class Toolbar
{
    protected $alertSeverities = [
        Logger::SEVERITY_ALERT,
        Logger::SEVERITY_ERROR,
        Logger::SEVERITY_EMERGENCY,
        Logger::SEVERITY_CRITICAL
    ];

    /**
     * @var ProfilerInterface
     */
    protected $profiler;

    /**
     * @var string
     */
    protected $title = 'Prophiler';

    /**
     * @var DataCollectorInterface[]
     */
    protected $dataCollectors = [];

    /**
     * @param ProfilerInterface $profiler
     */
    public function __construct(ProfilerInterface $profiler)
    {
        $this->setProfiler($profiler);
    }
    public function setTitle($title)
    {
        $this->title=$title;
    }

    /**
     * Render the toolbar
     */
    public function render()
    {
        $alerts = new ComponentFilteredIterator(
            $this->profiler,
            'Logger',
            ['severity' => $this->alertSeverities]
        );

        ob_start();
        $this->partial('toolbar', [
            'profiler' => $this->getProfiler(),
            'dataCollectors' => $this->getDataCollectors(),
            'aggregators' => $this->getProfiler()->getAggregators(),
            'alertCount' => count($alerts)
        ]);
        return ob_get_clean();
    }

    /**
     * Return css
     *
     * @return string
     */
    public function css()
    {
        return '<style>'.PHP_EOL.file_get_contents(__DIR__ . '/View/css/screen.css').PHP_EOL.'</style>';
    }

    /**
     * Return javascript
     *
     * @return string
     */
    public function js()
    {
        return '<script>'.PHP_EOL.file_get_contents(__DIR__ . '/View/js/profiler.js').PHP_EOL.'</script>';
    }

    /**
     * @param string $viewPath
     * @param array $params
     */
    public function partial($viewPath, array $params = [])
    {
        extract($params, EXTR_OVERWRITE);
        $viewScriptPath = __DIR__ . '/View/' . $viewPath . '.phtml';

        if (!file_exists($viewScriptPath)) {
            throw new \InvalidArgumentException(sprintf(
                'View not found: %s',
                $viewScriptPath
            ));
        }
        require $viewScriptPath;
    }

    /**
     * @return ProfilerInterface
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * @param ProfilerInterface $profiler
     */
    public function setProfiler($profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * Add a data collector to the profiler
     *
     * @param DataCollectorInterface $dataCollector
     * @return $this
     */
    public function addDataCollector(DataCollectorInterface $dataCollector)
    {
        $this->dataCollectors[] = $dataCollector;
    }

    /**
     * @return DataCollectorInterface[]
     */
    public function getDataCollectors()
    {
        return $this->dataCollectors;
    }
}
