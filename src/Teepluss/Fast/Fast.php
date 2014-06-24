<?php namespace Teepluss\Fast;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Config\Repository;
use Illuminate\Cache\CacheManager;

class Fast {

    /**
     * Cache driver.
     *
     * @var string
     */
    protected $driver;

    /**
     * Repository config.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * Cache manager.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Http request.
     *
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * Cache tags.
     *
     * @var array
     */
    protected $tags = array();

    /**
     * Debugging.
     *
     * @var boolean
     */
    protected $debug = false;

    /**
     * Default time to expire.
     *
     * @var integer
     */
    protected $expireInSecond = 60;

    /**
     * Maximum time to expire.
     *
     * @var integer
     */
    protected $maximumAliveInSecond = 86400;

    /**
     * Closure trigger.
     *
     * @var Closure
     */
    protected $trigger = null;

    /**
     * Construct.
     *
     * @param Repository   $config
     * @param CacheManager $cache
     * @param Request      $request
     */
    public function __construct(Repository $config, CacheManager $cache, Request $request)
    {
        $this->intialize($config->get('fast::fast'));

        $this->config = $config;

        $this->cache = $cache->driver($this->driver);

        $this->request = $request;
    }

    /**
     * Intialize config.
     *
     * @param  array $config
     * @return void
     */
    protected function intialize($config)
    {
        if ( ! is_array($config)) return false;

        foreach ($config as $key => $val)
        {
            if (isset($key))
            {
                $this->$key = $val;
            }
        }
    }

    /**
     * Enable debugging cache content.
     *
     * @param  boolean $enable
     * @return Fast
     */
    public function debug($enable = true)
    {
        $this->debug = $enable;

        return $this;
    }

    /**
     * Set up time cache expire.
     *
     * @param  integer $seconds
     * @return Fast
     */
    public function expireInSecond($seconds)
    {
        $this->expireInSecond = $seconds;

        return $this;
    }

    /**
     * Set up time cache expire in minute.
     *
     * @param  interger $minute
     * @return Fast
     */
    public function expireInMinute($minute)
    {
        $this->expireInSecond = ($minute * 60);

        return $this;
    }

    /**
     * Remember cache content.
     *
     * @param  string  $key
     * @param  Closure $callback
     * @return mixed
     */
    public function remember($key, Closure $callback)
    {
        $currentUrl = $this->request->fullUrl();

        // Allow only localhost to make best perform.
        if ( ! $this->isLocalhost($currentUrl))
        {
            throw new InvalidCachingURL('Fast will work on domain map to 127.0.0.1.');
        }

        // Data from cache is exists.
        if ($data = $this->cache->tags($this->tags)->get($key) and ! $this->debug)
        {
            // Get created at to compare expires.
            $_created_at = $data['_created_at'];

            // Current expired in x seconds.
            $expires = $_created_at + $this->expireInSecond;

            // Cache expired.
            if ($expires < time())
            {
                // If not a robot, so let trigger.
                if ( ! $this->isRobot())
                {
                    $processingKey = 'processing-'.$key;

                    // Do not double trigger.
                    if ( ! $this->cache->tags($this->tags)->get($processingKey))
                    {
                        $this->cache->tags($this->tags + array('processing'))->put($processingKey, true, 5);

                        $this->trigger($processingKey, $currentUrl);
                    }
                }
            }

            // Return data from cache to a human.
            if ( ! $this->isRobot())
            {
                return array_get($data, '_data');
            }
        }

        // Closure content.
        $content = $callback();

        $data = array(
            '_data'       => $content,
            '_created_at' => time()
        );

        // Put cache.
        $this->cache->tags($this->tags)->put($key, $data, ($this->maximumAliveInSecond / 60));

        return $data['_data'];
    }

    /**
     * Checking key cache existing.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->cache->tags($this->tags)->has($key);
    }

    /**
     * Remove cache.
     *
     * @param  string  $key
     * @return boolean
     */
    public function forget($key)
    {
        return $this->cache->tags($this->tags)->forget($key);
    }

    /**
     * Remove all cache.
     *
     * @return boolean
     */
    public function flush()
    {
        return $this->cache->tags($this->tags)->flush();
    }

    /**
     * Checking is robot to run trigger.
     *
     * @return boolean
     */
    protected function isRobot()
    {
        return isset($_GET['fast-robot']);
    }

    /**
     * Checking is localhost.
     *
     * @param  string  $url
     * @return boolean
     */
    protected function isLocalhost($url)
    {
        return gethostbyname(parse_url($url, PHP_URL_HOST)) == '127.0.0.1';
    }

    /**
     * Run trigger to make cache.
     *
     * @param  string $processingKey
     * @param  string $url
     * @return void
     */
    protected function trigger($processingKey, $url)
    {
        // Let the trigger know, this is robot.
        $url = (strpos($url, '?') === false) ? $url . '?fast-robot=1' : $url . '&fast-robot=1';

        $trigger = $this->trigger;

        $trigger($this, $url);

        $this->cache->tags($this->tags + array('processing'))->forget($processingKey);
    }

}
