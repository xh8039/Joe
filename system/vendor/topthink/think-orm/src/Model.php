<?php

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2025 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace think;

use ArrayAccess;
use Closure;
use JsonSerializable;
use think\contract\Arrayable;
use think\contract\Jsonable;
use think\db\BaseQuery as Query;
use think\db\Express;
use think\model\contract\Modelable;

/**
 * Class Model.
 *
 * @mixin Query
 *
 * @method static void  onAfterRead(Model $model)     after_read事件定义
 * @method static mixed onBeforeInsert(Model $model)  before_insert事件定义
 * @method static void  onAfterInsert(Model $model)   after_insert事件定义
 * @method static mixed onBeforeUpdate(Model $model)  before_update事件定义
 * @method static void  onAfterUpdate(Model $model)   after_update事件定义
 * @method static mixed onBeforeWrite(Model $model)   before_write事件定义
 * @method static void  onAfterWrite(Model $model)    after_write事件定义
 * @method static mixed onBeforeDelete(Model $model)  before_write事件定义
 * @method static void  onAfterDelete(Model $model)   after_delete事件定义
 * @method static void  onBeforeRestore(Model $model) before_restore事件定义
 * @method static void  onAfterRestore(Model $model)  after_restore事件定义
 */
abstract class Model implements JsonSerializable, ArrayAccess, Arrayable, Jsonable, Modelable
{
    use model\concern\Attribute;
    use model\concern\RelationShip;
    use model\concern\ModelEvent;
    use model\concern\TimeStamp;
    use model\concern\AutoWriteId;
    use model\concern\Conversion;

    /**
     * 数据是否存在.
     *
     * @var bool
     */
    private $exists = false;

    /**
     * 是否强制更新所有数据.
     *
     * @var bool
     */
    private $force = false;

    /**
     * 是否Replace.
     *
     * @var bool
     */
    private $replace = false;

    /**
     * 数据表后缀
     *
     * @var string
     */
    protected $suffix;

    /**
     * 更新条件.
     *
     * @var array
     */
    private $updateWhere;

    /**
     * 数据库配置.
     *
     * @var string
     */
    protected $connection;

    /**
     * 模型名称.
     *
     * @var string
     */
    protected $name;

    /**
     * 主键值
     *
     * @var string
     */
    protected $key;

    /**
     * 数据表名称.
     *
     * @var string
     */
    protected $table;

    /**
     * 设置实体模型对象.
     *
     * @var string|bool
     */
    protected $entityClass;

    protected $entity;

    /**
     * 初始化过的模型.
     *
     * @var array
     */
    protected static $initialized = [];

    /**
     * 全局查询范围.
     *
     * @var array
     */
    protected $globalScope = [];

    /**
     * 数据表延迟写入的字段
     *
     * @var array
     */
    protected $lazyFields = [];

    /**
     * 软删除字段默认值
     *
     * @var mixed
     */
    protected $defaultSoftDelete;

    /**
     * Db对象
     *
     * @var DbManager
     */
    protected static $db;

    /**
     * 容器对象的依赖注入方法.
     *
     * @var callable
     */
    protected static $invoker;

    /**
     * 服务注入.
     *
     * @var Closure[]
     */
    protected static $maker = [];

    /**
     * 方法注入.
     *
     * @var Closure[][]
     */
    protected static $macro = [];

    /**
     * 设置服务注入.
     *
     * @param Closure $maker
     *
     * @return void
     */
    public static function maker(Closure $maker)
    {
        static::$maker[] = $maker;
    }

    /**
     * 设置方法注入.
     *
     * @param string  $method
     * @param Closure $closure
     *
     * @return void
     */
    public static function macro(string $method, Closure $closure)
    {
        if (!isset(static::$macro[static::class])) {
            static::$macro[static::class] = [];
        }

        static::$macro[static::class][$method] = $closure;
    }

    /**
     * 设置Db对象
     *
     * @param DbManager $db Db对象
     *
     * @return void
     */
    public static function setDb(DbManager $db)
    {
        self::$db = $db;
    }

    /**
     * 设置容器对象的依赖注入方法.
     *
     * @param callable $callable 依赖注入方法
     *
     * @return void
     */
    public static function setInvoker(callable $callable): void
    {
        self::$invoker = $callable;
    }

    /**
     * 调用反射执行模型方法 支持参数绑定.
     *
     * @param mixed $method
     * @param array $vars   参数
     *
     * @return mixed
     */
    public function invoke($method, array $vars = [])
    {
        if (is_string($method)) {
            $method = [$this, $method];
        }

        if (self::$invoker) {
            $call = self::$invoker;

            return $call($method instanceof Closure ? $method : Closure::fromCallable($method), $vars);
        }

        return call_user_func_array($method, $vars);
    }

    /**
     * 架构函数.
     *
     * @param array|object $data 数据
     */
    public function __construct(array | object $data = [])
    {
        // 设置数据
        $this->data($data);

        // 记录原始数据
        $this->origin = $this->data;

        if (empty($this->name)) {
            // 当前模型名
            $name       = str_replace('\\', '/', static::class);
            $this->name = basename($name);
        }

        if (!empty(static::$maker)) {
            foreach (static::$maker as $maker) {
                call_user_func($maker, $this);
            }
        }

        // 执行初始化操作
        $this->initialize();
    }

    public function getOption(string $name)
    {
        return property_exists($this, $name) ? $this->$name : null;
    }

    /**
     * 获取当前模型名称.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * 设置当前模型名称.
     *
     * @param string $name 模型名称
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * 获取模型类名.
     *
     * @return string
     */
    public static function getEntityClass()
    {
        $entity = str_replace('\\model', '\\entity', static::class);
        return class_exists($entity) ? $entity: static::class;
    }

    /**
     * 创建新的模型实例.
     *
     * @param array $data    数据
     * @param mixed $where   更新条件
     * @param array $options 参数
     *
     * @return Modelable
     */
    public function newInstance(array $data = [], $where = null, array $options = []): Modelable
    {
        $class = static::getEntityClass();
        $model = new $class($data, $this);

        if ($this->connection) {
            $model->setConnection($this->connection);
        }

        if ($this->suffix) {
            $model->setSuffix($this->suffix);
        }

        if (empty($data)) {
            return $model;
        }

        if (!$this->entity) {
            $model->exists(true);
            $model->setUpdateWhere($where);
        }

        $this->trigger('AfterRead');

        return $model;
    }

    /**
     * 获取当前模型的实体模型实例.
     *
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * 设置实体模型实例.
     *
     * @param Entity $entity 实体模型实例
     *
     * @return void
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    /**
     * 设置模型的更新条件.
     *
     * @param mixed $where 更新条件
     *
     * @return $this
     */
    public function setUpdateWhere($where)
    {
        if (!empty($where)) {
            $this->updateWhere = $where;
        }
        return $this;
    }

    /**
     * 设置当前模型的数据库连接.
     *
     * @param string $connection 数据表连接标识
     *
     * @return $this
     */
    public function setConnection(string $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * 获取当前模型的数据库连接标识.
     *
     * @return string
     */
    public function getConnection(): string
    {
        return $this->connection ?: '';
    }

    /**
     * 设置当前模型数据表的后缀
     *
     * @param string $suffix 数据表后缀
     *
     * @return $this
     */
    public function setSuffix(string $suffix)
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * 获取当前模型的数据表后缀
     *
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix ?? '';
    }

    /**
     * 获取当前模型的数据库查询对象
     *
     * @param array $scope 设置不使用的全局查询范围
     *
     * @return Query
     */
    public function db($scope = []): Query
    {
        /** @var Query $query */
        $query = self::$db->connect($this->connection)
            ->name($this->name)
            ->pk($this->pk);

        if (!empty($this->autoInc)) {
            $query->autoinc(is_string($this->autoInc) ? $this->autoInc : $this->pk);
        }

        if (!empty($this->table)) {
            $query->table($this->table . $this->suffix);
        } elseif (!empty($this->suffix)) {
            $query->suffix($this->suffix);
        }

        $query->model($this)
            ->json($this->json, $this->jsonAssoc)
            ->setFieldType(array_merge($this->schema, $this->jsonType))
            ->setKey($this->getKey())
            ->readonly($this->readonly)
            ->lazyFields($this->lazyFields);

        // 软删除
        if (property_exists($this, 'withTrashed') && !$this->withTrashed) {
            $this->withNoTrashed($query);
        }

        // 全局作用域
        if (is_array($scope)) {
            $globalScope = array_diff($this->globalScope, $scope);
            $query->scope($globalScope);
        }

        // 返回当前模型的数据库查询对象
        return $query;
    }

    /**
     *  初始化模型.
     *
     * @return void
     */
    private function initialize(): void
    {
        if (!isset(static::$initialized[static::class])) {
            static::$initialized[static::class] = true;
            static::init();
        }
    }

    /**
     * 初始化处理.
     *
     * @return void
     */
    protected static function init()
    {
    }

    protected function checkData(): void
    {
    }

    protected function checkResult($result): void
    {
    }

    /**
     * 更新是否强制写入数据 而不做比较（亦可用于软删除的强制删除）.
     *
     * @param bool $force
     *
     * @return $this
     */
    public function force(bool $force = true)
    {
        $this->force = $force;

        return $this;
    }

    /**
     * 判断force.
     *
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }

    /**
     * 新增数据是否使用Replace.
     *
     * @param bool $replace
     *
     * @return $this
     */
    public function replace(bool $replace = true)
    {
        $this->replace = $replace;

        return $this;
    }

    /**
     * 刷新模型数据.
     *
     * @param bool $relation 是否刷新关联数据
     *
     * @return $this
     */
    public function refresh(bool $relation = false)
    {
        if ($this->exists) {
            $data = $this->db()->find($this->getKey())->getData();
            if ($this->entity) {
                $this->entity->data($data);
            } else {
                $this->data   = $data;
                $this->origin = $this->data;
                $this->get    = [];

                if ($relation) {
                    $this->relation = [];
                }
            }
        }

        return $this;
    }

    /**
     * 设置数据是否存在.
     *
     * @param bool $exists
     *
     * @return $this
     */
    public function exists(bool $exists = true)
    {
        $this->exists = $exists;

        return $this;
    }

    /**
     * 判断数据是否存在数据库.
     *
     * @return bool
     */
    public function isExists(): bool
    {
        return $this->exists;
    }

    /**
     * 判断模型是否为空.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        if ($this->entity) {
            return $this->entity->isEmpty();
        }
        return empty($this->data);
    }

    /**
     * 字段值增长
     *
     * @param string $field 字段名
     * @param float  $step  增长值
     * @param int    $lazyTime 延迟时间（秒）
     *
     * @return $this
     */
    public function inc(string $field, float $step = 1, int $lazyTime = 0)
    {
        $this->setAttr($field, new Express('+', $step, $lazyTime));
        return $this;
    }

    /**
     * 字段值减少.
     *
     * @param string $field 字段名
     * @param float  $step  增长值
     * @param int    $lazyTime 延迟时间（秒） 
     *
     * @return $this
     */
    public function dec(string $field, float $step = 1, int $lazyTime = 0)
    {
        $this->setAttr($field, new Express('-', $step, $lazyTime));
        return $this;
    }

    /**
     * 保存当前数据对象
     *
     * @param array|object  $data     数据
     * @param string $sequence 自增序列名
     *
     * @return bool
     */
    public function save(array | object $data = [], ?string $sequence = null): bool
    {
        if (!$this->entity) {
            if ($data instanceof Model) {
                $data = $data->getData();
            } elseif (is_object($data)) {
                $data = get_object_vars($data);
            }

            // 数据对象赋值
            $this->setAttrs($data);
            $data = $this->data;
        }

        if ($this->isEmpty() || false === $this->trigger('BeforeWrite')) {
            return false;
        }

        $result = $this->exists ? $this->updateData($data) : $this->insertData($data, $sequence);

        if (false === $result) {
            return false;
        }

        // 写入回调
        $this->trigger('AfterWrite');

        if (!$this->entity) {
            foreach ($this->data as $name => &$val) {
                if ($val instanceof Express) {
                    $step   = $val->getStep();
                    $origin = $this->origin[$name];
                    $val    = match ($val->getType()) {
                        '+'         => $origin + $step,
                        '-'         => $origin - $step,
                        '*'         => $origin * $step,
                        '/'         => $origin / $step,
                        default     => $origin,
                    };
                }
            }
            // 重新记录原始数据
            $this->origin = $this->data;
            $this->get    = [];
        }

        return true;
    }

    /**
     * 检查数据是否允许写入.
     *
     * @return array
     */
    protected function checkAllowFields(): array
    {
        // 检测字段
        if (empty($this->field)) {
            if ($this->entity) {
                return array_keys($this->entity->getFields());
            }

            if (!empty($this->schema)) {
                $this->field = array_keys(array_merge($this->schema, $this->jsonType));
            } else {
                $query = $this->db();
                $table = $this->table ? $this->table . $this->suffix : $query->getTable();

                $this->field = $query->getConnection()->getTableFields($table);
            }

            return $this->field;
        }

        $field = $this->field;

        if ($this->autoWriteTimestamp) {
            array_push($field, $this->createTime, $this->updateTime);
        }

        if (!empty($this->disuse)) {
            // 废弃字段
            $field = array_diff($field, $this->disuse);
        }

        return $field;
    }

    /**
     * 保存写入数据.
     *
     * @return bool
     */
    protected function updateData(array $data): bool
    {
        // 事件回调
        if (false === $this->trigger('BeforeUpdate')) {
            return false;
        }

        $this->checkData();

        // 获取有更新的数据
        if (!$this->entity) {
            $data = $this->getChangedData($data);
        }

        if (empty($data)) {
            // 关联更新
            if (!empty($this->relationWrite)) {
                $this->autoRelationUpdate();
            }

            return true;
        }

        if (!$this->entity && $this->autoWriteTimestamp && $this->updateTime) {
            // 自动写入更新时间
            $data[$this->updateTime] = $this->autoWriteTimestamp();
            if ($this->entity) {
                $updateTimeField                = $this->updateTime;
                $this->entity->$updateTimeField = $data[$this->updateTime];
            } else {
                $this->data[$this->updateTime] = $data[$this->updateTime];
            }
        }

        // 检查允许字段
        $allowFields = $this->checkAllowFields();

        foreach ($this->relationWrite as $val) {
            if (!is_array($val)) {
                continue;
            }

            foreach ($val as $key) {
                if (isset($data[$key])) {
                    unset($data[$key]);
                }
            }
        }

        // 模型更新
        $db = $this->db(null);

        $db->transaction(function () use ($data, $allowFields, $db) {
            $where  = $this->getWhere();
            $result = $db->where($where)
                ->strict(false)
                ->cache(true)
                ->setOption('key', $this->key)
                ->field($allowFields)
                ->update($data);

            $this->checkResult($result);

            // 关联更新
            if (!empty($this->relationWrite)) {
                $this->autoRelationUpdate();
            }
        });

        // 更新回调
        $this->trigger('AfterUpdate');

        return true;
    }

    /**
     * 新增写入数据.
     *
     * @param string $sequence 自增名
     *
     * @return bool
     */
    protected function insertData(array $data, ?string $sequence = null): bool
    {
        if (false === $this->trigger('BeforeInsert')) {
            return false;
        }

        $this->checkData();

        // 主键自动写入
        if ($this->isAutoWriteId()) {
            $pk = $this->getPk();
            if (is_string($pk) && !isset($this->data[$pk])) {
                $data[$pk] = $this->autoWriteId();
                if ($this->entity) {
                    $this->entity->$pk = $data[$pk];
                } else {
                    $this->data[$pk] = $data[$pk];
                }
            }
        }

        if (!$this->entity) {
            // 时间字段自动写入
            if ($this->autoWriteTimestamp) {
                foreach ([$this->createTime, $this->updateTime] as $field) {
                    if ($field && !array_key_exists($field, $this->data)) {
                        $data[$field]       = $this->autoWriteTimestamp();
                        $this->data[$field] = $data[$field];
                    }
                }
            }

            // 自动（使用修改器）写入字段
            if (!empty($this->insert)) {
                foreach ($this->insert as $name => $val) {
                    $field = is_string($name) ? $name : $val;
                    if (!isset($data[$field])) {
                        if (is_string($name)) {
                            $data[$field]       = $val;
                            $this->data[$field] = $val;
                        } else {
                            $this->setAttr($field, null);
                            $data[$field] = $this->data[$field];
                        }
                    }
                }
            }
        }

        // 检查允许字段
        $allowFields = $this->checkAllowFields();

        $db = $this->db();

        $db->transaction(function () use ($data, $sequence, $allowFields, $db) {
            $result = $db->strict(false)
                ->field($allowFields)
                ->replace($this->replace)
                ->sequence($sequence)
                ->insert($data, true);

            // 获取自动增长主键
            if ($result && !$this->isAutoWriteId()) {
                $pk = $this->getPk();

                if (is_string($pk) && (!isset($data[$pk]) || '' == $data[$pk])) {
                    if ($this->entity) {
                        $this->entity->setKey($result);
                    } else {
                        unset($this->get[$pk]);
                        $this->data[$pk] = $result;
                    }
                }
            }

            // 关联写入
            if (!empty($this->relationWrite)) {
                $this->autoRelationInsert();
            }
        });

        // 标记数据已经存在
        $this->exists = true;
        $this->origin = $this->data;

        // 新增回调
        $this->trigger('AfterInsert');

        return true;
    }

    /**
     * 获取当前的更新条件.
     *
     * @return mixed
     */
    public function getWhere()
    {
        $pk = $this->getPk();

        if ($this->key) {
            $where = [$pk => $this->key];
        } elseif (is_string($pk) && isset($this->origin[$pk])) {
            $where     = [$pk => $this->origin[$pk]];
            $this->key = $this->origin[$pk];
        } elseif (is_array($pk)) {
            foreach ($pk as $field) {
                if (isset($this->origin[$field])) {
                    $where[] = [$field, '=', $this->origin[$field]];
                }
            }
        }

        if (empty($where)) {
            $where = empty($this->updateWhere) ? null : $this->updateWhere;
        }

        return $where;
    }

    /**
     * 保存多个数据到当前数据对象
     *
     * @param iterable $dataSet 数据
     * @param bool     $replace 是否自动识别更新和写入
     *
     * @throws \Exception
     *
     * @return Collection
     */
    public function saveAll(iterable $dataSet, bool $replace = true): Collection
    {
        $db = $this->db();

        $result = $db->transaction(function () use ($replace, $dataSet) {
            $pk = $this->getPk();

            $result = [];
            $suffix = $this->getSuffix();

            foreach ($dataSet as $key => $data) {
                if ($replace) {
                    $exists = true;
                    foreach ((array) $pk as $field) {
                        if (is_string($field) && !isset($data[$field])) {
                            $exists = false;
                        }
                    }
                }

                if ($replace && !empty($exists)) {
                    $result[$key] = static::update($data, [], [], $suffix);
                } else {
                    $result[$key] = static::create($data, $this->field, $this->replace, $suffix);
                }
            }

            return $result;
        });

        return $this->toCollection($result);
    }

    /**
     * 删除当前的记录.
     *
     * @return bool
     */
    public function delete(): bool
    {
        if (!$this->exists || $this->isEmpty() || false === $this->trigger('BeforeDelete')) {
            return false;
        }

        // 读取更新条件
        $where = $this->getWhere();
        $db    = $this->db();

        $db->transaction(function () use ($where, $db) {
            // 删除当前模型数据
            $db->where($where)->delete();

            // 关联删除
            if (!empty($this->relationWrite)) {
                $this->autoRelationDelete();
            }
        });

        $this->trigger('AfterDelete');

        $this->exists = false;

        return true;
    }

    /**
     * 写入数据.
     *
     * @param array|object  $data 数据
     * @param array  $allowField  允许字段
     * @param bool   $replace     使用Replace
     * @param string $suffix      数据表后缀
     *
     * @return static
     */
    public static function create(array | object $data, array $allowField = [], bool $replace = false, string $suffix = ''): Modelable
    {
        $class = static::getEntityClass();
        $model = new $class();

        if (!empty($allowField)) {
            $model->allowField($allowField);
        }

        if (!empty($suffix)) {
            $model->setSuffix($suffix);
        }

        $model->replace($replace);
        $model->save($data);

        return $model;
    }

    /**
     * 更新数据.
     *
     * @param array|object  $data 数据数组
     * @param mixed  $where       更新条件
     * @param array  $allowField  允许字段
     * @param string $suffix      数据表后缀
     *
     * @return static
     */
    public static function update(array | object $data, $where = [], array $allowField = [], string $suffix = ''): Modelable
    {
        $class = static::getEntityClass();
        $model = new $class();

        if (!empty($allowField)) {
            $model->allowField($allowField);
        }

        if (!empty($where)) {
            $model->setUpdateWhere($where);
        }

        if (!empty($suffix)) {
            $model->setSuffix($suffix);
        }

        $model->exists(true);
        $model->save($data);

        return $model;
    }

    /**
     * 删除记录.
     *
     * @param mixed $data  主键列表 支持闭包查询条件
     * @param bool  $force 是否强制删除
     *
     * @return bool
     */
    public static function destroy($data, bool $force = false): bool
    {
        if (empty($data) && 0 !== $data) {
            return false;
        }

        $model = new static();
        $query = $model->db();

        if (is_array($data) && key($data) !== 0) {
            $query->where($data);
            $data = [];
        } elseif ($data instanceof Closure) {
            $data($query);
            $data = [];
        }

        $resultSet = $query->select((array) $data);

        foreach ($resultSet as $result) {
            $result->force($force)->delete();
        }

        return true;
    }

    /**
     * 解序列化后处理.
     */
    public function __wakeup()
    {
        $this->initialize();
    }

    /**
     * 修改器 设置数据对象的值
     *
     * @param string $name  名称
     * @param mixed  $value 值
     *
     * @return void
     */
    public function __set(string $name, $value): void
    {
        if ($this->entity) {
            $this->entity->$name = $value;
        } else {
            $this->setAttr($name, $value);
        }
    }

    /**
     * 获取器 获取数据对象的值
     *
     * @param string $name 名称
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        if ($this->entity) {
            return $this->entity->$name;
        }
        return $this->getAttr($name);
    }

    /**
     * 检测数据对象的值
     *
     * @param string $name 名称
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        if ($this->entity) {
            return isset($this->entity->$name);
        }
        return !is_null($this->getAttr($name));
    }

    /**
     * 销毁数据对象的值
     *
     * @param string $name 名称
     *
     * @return void
     */
    public function __unset(string $name): void
    {
        unset(
            $this->data[$name],
            $this->get[$name],
            $this->relation[$name]
        );
    }

    // ArrayAccess
    public function offsetSet(mixed $name, mixed $value): void
    {
        $this->setAttr($name, $value);
    }

    public function offsetExists(mixed $name): bool
    {
        return $this->__isset($name);
    }

    public function offsetUnset(mixed $name): void
    {
        $this->__unset($name);
    }

    public function offsetGet(mixed $name): mixed
    {
        return $this->getAttr($name);
    }

    /**
     * 设置不使用的全局查询范围.
     *
     * @param array $scope 不启用的全局查询范围
     *
     * @return Query
     */
    public static function withoutGlobalScope(?array $scope = null): Query
    {
        $model = new static();

        return $model->db($scope);
    }

    /**
     * 切换后缀进行查询.
     *
     * @param string $suffix 切换的表后缀
     *
     * @return Model
     */
    public static function suffix(string $suffix)
    {
        $model = new static();
        $model->setSuffix($suffix);

        return $model;
    }

    /**
     * 切换数据库连接进行查询.
     *
     * @param string $connection 数据库连接标识
     *
     * @return Model
     */
    public static function connect(string $connection)
    {
        $model = new static();
        $model->setConnection($connection);

        return $model;
    }

    /**
     * 创建一个查询对象
     * @return Query
     */
    public static function query(): Query
    {
        return (new static())->db();
    }

    public function __call($method, $args)
    {
        if (isset(static::$macro[static::class][$method])) {
            return call_user_func_array(static::$macro[static::class][$method]->bindTo($this, static::class), $args);
        }

        if ($this->exists && strtolower($method) == 'withattr') {
            return call_user_func_array([$this, 'withFieldAttr'], $args);
        }

        return call_user_func_array([$this->db(), $method], $args);
    }

    public static function __callStatic($method, $args)
    {
        if (isset(static::$macro[static::class][$method])) {
            return call_user_func_array(static::$macro[static::class][$method]->bindTo(null, static::class), $args);
        }

        $model = new static();

        return call_user_func_array([$model->db(), $method], $args);
    }
}
