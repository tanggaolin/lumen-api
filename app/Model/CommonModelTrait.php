<?php
/**
 * Description: Model通用方法Trait
 */

namespace App\Traits;


Trait CommonModelTrait
{
    /**
     * 获取单条数据
     * @param array $whereParam
     * @return array
     */
    public function getOne(array $whereParam, array $columns = []): array
    {
        $columns = empty($columns) ? '*' : $columns;
        $res = $this->select($columns)->where($whereParam)->first();
        return $res ? $res->toArray() : [];
    }

    /**
     * 获取多条列表数据
     * @param array $whereParam
     * @return array
     */
    public function getList(array $whereParam, array $columns = []): array
    {
        $columns = empty($columns) ? '*' : $columns;
        $res = $this->select($columns)->where($whereParam)->get();
        return $res ? $res->toArray() : [];
    }

    /**
     * 获取In类型的列表
     * @param array $inWhereParam
     * @param array $columns
     * @param array $whereParam
     * @return array
     */
    public function getListIn(array $inWhereParam, array $columns = [], array $whereParam = []): array
    {
        $columns = empty($columns) ? '*' : $columns;
        $res = $this->select($columns);
        foreach ($inWhereParam as $columns => $arrValues) {
            $res = $res->whereIn($columns, $arrValues);
        }
        if (!empty($whereParam)) {
            $res = $res->where($whereParam);
        }
        $res = $res->get()->toArray();
        return $res;
    }

    /**
     * 更新数据
     * @param array $updateValues
     * @param array $whereParam
     * @return array
     */
    public function updateValues(array $updateValues = [], array $whereParam = [])
    {
        return empty($whereParam) ? $this->update($updateValues)
            : $this->where($whereParam)->update($updateValues);
    }

    /**
     * 根据主键id获取数据
     * @param int $id
     * @param array $columns
     * @param array $condition
     * @return array
     */
    public function getById(int $id, array $columns = [], array $condition = [])
    {
        $columns = empty($columns) ? '*' : $columns;
        $md = $this->select($columns)->where([$this->primaryKey => $id]);
        if (!empty($condition)) {
            $md = $md->where($condition);
        }
        $result = $md->first();

        return $result ? $result->toArray() : [];
    }

    /**
     * 根据主键ids批量获取数据
     * @param int $id
     * @param array $columns
     * @param array $condition
     * @return array
     */
    public function getByIds(array $ids, array $columns = [], array $condition = [])
    {
        $columns = empty($columns) ? '*' : $columns;
        $md = $this->select($columns)->whereIn($this->primaryKey, $ids);
        if (!empty($condition)) {
            $md = $md->where($condition);
        }
        $result = $md->get();

        return $result ? $result->toArray() : [];
    }

    /**
     * 根据主键id更新数据
     * @param int $id
     * @param array $updateValues
     * @param array $condition
     * @return mixed
     */
    public function updateById(int $id, array $updateValues = [], array $condition = [])
    {
        $md = $this->where([$this->primaryKey => $id]);
        if (!empty($condition)) {
            $md = $md->where($condition);
        }
        $result = $md->update($updateValues);

        return $result;
    }

    /**
     * 根据主键ids批量更新数据
     * @param array $ids
     * @param array $updateValues
     * @param array $condition
     * @return mixed
     */
    public function updateByIds(array $ids, array $updateValues = [], array $condition = [])
    {
        $md = $this->whereIn($this->primaryKey, $ids);
        if (!empty($condition)) {
            $md = $md->where($condition);
        }
        $result = $md->update($updateValues);

        return $result;
    }
}