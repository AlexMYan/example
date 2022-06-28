<?php


namespace Extra;

class Helper
{

    static $cacheIblockIds = []; // [iblockCode => iblockId]

    /**
     * Получение свойства элемента по CODE
     *
     *
     * @param int $orderID
     * @param string $propertyCode
     *
     * @return false|propertyValue
     */
    function getElementProperty(int $ID, string $propertyCode)
    {
        $resElement = \CIBlockElement::GetList([], ['ID' => $ID], false, false, ["PROPERTY_" . $propertyCode]);

        if ($element = $resElement->getNext()) {
            return $element["PROPERTY_" . $propertyCode . "_VALUE"];
        }

        return false;
    }


    /**
     * Получение множественного свойства элемента по CODE
     *
     *
     * @param int $orderID
     * @param string $propertyCode
     *
     * @return false|propertyValues
     */
    function getElementPropertyMulti(int $IBlockID, int $ID, string $propertyCode)
    {
        $result=[];

        $resElement = \CIBlockElement::GetProperty($IBlockID, $ID, "sort", "asc", array("CODE" => $propertyCode));
        while ($ob = $resElement->GetNext())
        {
            $result[] = $ob;
        }

        if(!empty($result)){
            return $result;
        }

        return false;
    }

    /**
     * Обновляет свойства элемента по CODE => VALUE
     *
     *
     * @param int $orderID
     * @param array $properties => $value
     *
     * @return true|false
     */
    function updateElementProperty(int $ID, $properties)
    {
        if (!empty($ID) && !empty($properties)) {
            \CIBlockElement::SetPropertyValuesEx($ID, false, $properties);
            return true;
        }

        return false;
    }

    /**
     * Получение свойства заказа по CODE
     *
     *
     * @param int $orderID
     * @param string $propertyCode
     *
     * @return false|propertyValue
     */
    function getOrderProperty(int $orderID, string $propertyCode)
    {
        if ($arOrderProps = \CSaleOrderProps::GetList(array(), array("CODE" => $propertyCode))->Fetch()) {
            $db_vals = \CSaleOrderPropsValue::GetList(array(), array("ORDER_ID" => $orderID, "ORDER_PROPS_ID" => $arOrderProps["ID"]));

            if ($arVals = $db_vals->Fetch()) {
                return $arVals["VALUE"];
            }
        }

        return false;
    }



    /**
     * Возвращает ID инфоблока по симаольному коду
     *
     * При первом обращении получает все ID инфоюлоков
     * (для того что бы уменьшить кол-во обращений к БД)
     *
     * @param string $iblockCode
     * @return false|mixed
     */
    function getIblockId(string $iblockCode)
    {
        $result = false;

        if (!empty($id = self::$cacheIblockIds[$iblockCode])) {
            $result = $id;
        } else if (\Bitrix\Main\Loader::includeModule('iblock')) {
            $o = \Bitrix\Iblock\IblockTable::getList(['select' => ['ID', 'CODE']]);
            while ($r = $o->fetch()) {
                if (!empty($code = $r['CODE'])) {
                    self::$cacheIblockIds[$code] = $r['ID'];
                }
            }

            if (!empty($id = self::$cacheIblockIds[$iblockCode])) {
                $result = $id;
            }
        }

        return $result;
    }


}
