<?php

namespace ApiSerwer\Model;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;

class Nieruchomosci
{
    private int $idUzytkownika = 1;

    /**
     * @var Adapter
     */
    private Adapter $adapter;

    private string $klucz;

    /**
     * @param \Laminas\Db\Adapter\Adapter $adapter
     * @param array                       $config
     */
    public function __construct(Adapter $adapter, array $config)
    {
        $this->adapter = $adapter;
        $this->klucz = $config['klucz_api'];
    }

    /**
     * Dodaje nieruchomość.
     *
     * @param string $klucz
     * @param array  $dane
     * @return integer
     * @throws \SoapFault
     */
    public function dodaj(string $klucz, array $dane): int
    {
        $this->sprawdzLogowanie($klucz);

        $sql = new Sql($this->adapter);
        $insert = $sql->insert('nieruchomosci');
        $insert->values([
            'id_uzytkownika' => $this->idUzytkownika,
            'typ_nieruchomosci' => $dane['typ_nieruchomosci'],
            'typ_oferty' => $dane['typ_oferty'],
            'numer' => $dane['numer'],
            'cena' => $dane['cena'],
            'powierzchnia' => str_replace(',', '.', $dane['powierzchnia']),
            'id_miasto' => $dane['id_miasto'],
        ]);
        $sqlString = $sql->buildSqlString($insert);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        try {
            return $wynik->getGeneratedValue();
        } catch (\Exception $e) {
            throw new \SoapFault('Server', 'Nie można dodać nieruchomości');
        }
    }
    /**
     * Aktualizuje nieruchomość.
     *
     * @param string $klucz
     * @param int $id
     * @param array  $dane
     * @return integer
     * @throws \SoapFault
     */
    public function aktualizuj(string $klucz, int $id, array $dane): int
    {
        $this->sprawdzLogowanie($klucz);
        $sql = new Sql($this->adapter);
        $update = $sql->update('nieruchomosci');
        $update->set([
            'id_uzytkownika' => $this->idUzytkownika,
            'typ_nieruchomosci' => $dane['typ_nieruchomosci'],
            'typ_oferty' => $dane['typ_oferty'],
            'numer' => $dane['numer'],
            'cena' => $dane['cena'],
            'powierzchnia' => str_replace(',', '.', $dane['powierzchnia']),
            'id_miasto' => $dane['id_miasto'],
        ]);
        $update->where(['id' => $id]);

        $sqlString = $sql->buildSqlString($update);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        try {
            return $wynik->getGeneratedValue();
        } catch (\Exception $e) {
            throw new \SoapFault('Server', 'Nie można zmienić nieruchomości');
        }
    }

    /**
     * Usuwa nieruchomość.
     *
     * @param string $klucz
     * @param int $id
     * @return boolean
     * @throws \SoapFault
     */
    public function usun(string $klucz, int $id): bool
    {
        $this->sprawdzLogowanie($klucz);

        $sql = new Sql($this->adapter);
        $delete = $sql->delete('nieruchomosci');
        $delete->where(['id' => $id]);

        $sqlString = $sql->buildSqlString($delete);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        return true;
//        try {
//            return $wynik->getGeneratedValue();
//        } catch (\Exception $e) {
//            throw new \SoapFault('Server', 'Nie można usunąć nieruchomości');
//        }
    }

    /**
     * Pobiera listę nieruchomości.
     *
     * @param string $klucz
     * @return array
     * @throws \SoapFault
     */
    public function pobierzWszystko(string $klucz): array
    {
        $this->sprawdzLogowanie($klucz);

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(['n' => 'nieruchomosci']);
        $select->join(['m' => 'miasta'], 'n.id_miasto = m.id', ['miasto']);
        $select->order('n.numer');


        $sqlString = $sql->buildSqlString($select);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        return $wynik->toArray();
    }

    /**
     * Pobiera szczegóły pojedynczej nieruchomości.
     *
     * @param string $klucz Klucz API
     * @param integer $id Identyfikator nieruchomości do wyświetlenia
     * @return array Szczegóły nieruchomości
     * @throws \SoapFault Informacja o ewentualnym błędzie
     */
    public function pobierzJeden(string $klucz, int $id): array
    {
        $this->sprawdzLogowanie($klucz);

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(['n' => 'nieruchomosci']);
        $select->where(['id' => $id]);

        $sqlString = $sql->buildSqlString($select);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);

        return $wynik->count() ? (array) $wynik->current() : [];
    }


    /**
     * Sprawdza klucz logowania.
     *
     * @param string $klucz
     * @throws \SoapFault
     */
    private function sprawdzLogowanie(string $klucz): void
    {
        if ($klucz != $this->klucz) {
            throw new \SoapFault('Server', 'Brak autoryzacji');
        }
    }


    /**
     * Pobiera listę miejscowości.
     *
     * @param string $klucz
     * @return array
     * @throws \SoapFault
     */
    public function pobierzMiasta(string $klucz): array
    {
        $this->sprawdzLogowanie($klucz);

        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from(['m' => 'miasta']);

        $sqlString = $sql->buildSqlString($select);
        $wynik = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        $temp = [];
        foreach ($wynik as $miasto) {
            $temp[$miasto->id] = $miasto->miasto;
        }
        return $temp;
    }
}