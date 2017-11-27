<?php

namespace PFinal\Storage;

class Local implements StorageInterface
{
    protected $basePath;
    protected $baseUrl;

    public function __construct(array $config = array())
    {
        foreach ($config as $key => $item) {
            $this->$key = $item;
        }
    }

    /**
     * 保存文件
     *
     * @param string $key
     * @param $data
     * @return bool
     */
    public function put($key, $data)
    {
        $filename = $this->getFullName($key);
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }
        $size = file_put_contents($filename, $data, LOCK_EX);
        return $size !== false;
    }

    /**
     * 返回文件外链
     * @param $key
     * @param string|null $rule
     * @return string
     */
    public function url($key, $rule = null)
    {
        //todo 处理rule

        return $this->baseUrl . $key;
    }

    /**
     * 重命名
     *
     * @param $key
     * @param $newKey
     * @return bool
     */
    public function rename($key, $newKey)
    {
        $newFile = $this->getFullName($newKey);
        if (!file_exists(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        return rename($this->getFullName($key), $newFile);
    }

    /**
     * 复制
     *
     * @param $key
     * @param $newKey
     * @return bool
     */
    public function copy($key, $newKey)
    {
        $newFile = $this->getFullName($newKey);
        if (!file_exists(dirname($newFile))) {
            mkdir(dirname($newFile), 0777, true);
        }

        return copy($this->getFullName($key), $newFile);
    }

    /**
     * 完整文件名
     * @param $key
     * @return string
     */
    protected function getFullName($key)
    {
        //todo 验证不能到basePath的上级

        return rtrim($this->basePath, '/\\') . DIRECTORY_SEPARATOR . $key;
    }

    /**
     * 删除文件
     *
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return unlink($this->getFullName($key));
    }

    /**
     * 错误消息
     *
     * @return string
     */
    public function error()
    {
        // TODO: Implement error() method.
    }
}