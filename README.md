 public function getReportCommissionIns($data = null) {
         
        $query = "
            select d.*, round(d.wartosc_netto_Pln/d.wartosc_Zk_wal, 2) as kurs from (
            select  
                substr(EKOOKNA_KOD_WPX(vb_fr_zamow.id_zamowienia),1,50) kod_win_Pro
                ,substr((select k.kod_alfa from kontrahenci k where k.numer_kontrahenta=vb_fr_zamow.numer_platnika),1,50) KRAJ
                ,numer_zamowienia
                ,'' as data
                , to_char(termin,'YYYY-MM-DD') as termin

                ,(select listagg(to_char(data_wystawienia,'YYYY-MM-DD'),',')WITHIN GROUP (order by 1)
                 from (select distinct  f.data_wystawienia   FROM vb_analiza_fs f
                          WHERE f.id_firmy = vb_fr_zamow.id_firmy
                                AND f.p_NUMER_ZAMOWIENIA_ZK = vb_fr_zamow.numer_zamowienia))   DATA_wystawienia_faktury

                , zbyt_powiazania.f_lista_faktur('1',vb_fr_zamow.numer_zamowienia) as numer_faktury
                ,nvl((select sum(pzk.WARTOSC_DEWIZ) from pozycje_zamowien_klientow pzk where pzk.ID_FIRMY=VB_FR_ZAMOW.ID_FIRMY and pzk.ID_ZAMOWIENIA=VB_FR_ZAMOW.ID_ZAMOWIENIA),0) wartosc_Zk_wal
                ,(select sum(decode((pzk.WARTOSC_NETTO),0,((pzk.WARTOSC_DEWIZ)*4.2),(pzk.WARTOSC_NETTO))) 
                   from pozycje_zamowien_klientow pzk where pzk.ID_FIRMY=VB_FR_ZAMOW.ID_FIRMY and pzk.ID_ZAMOWIENIA=VB_FR_ZAMOW.ID_ZAMOWIENIA) wartosc_netto_Pln
                ,'' as Kurs
                ,'' as wartosc_zamowienia_winpro_waluta
                ,'' as cennik
                ,NUMER_ZAMOWIENIA_WG_KLIENTA
                ,substr((select h.nazwisko  from handlowcy h where vb_fr_zamow.kod_handlowca=h.KOD_HANDLOWCA),1,50) PRZEDSTAWICIEL
                FROM vb_fr_zamow 

                where id_firmy =1                 
                and substr(vb_fr_zamow.kod_rodzaju_zamowienia,1,4) not in ('REKL')
                and substr(vb_fr_zamow.kod_rodzaju_zamowienia,1,3) not in ('BRK','SEW','KUR')
                and nvl(vb_fr_zamow.status_user,'-')<>'19'
                and 
                kod_rodzaju_zamowienia NOT IN ('TRA','SEW', 'TRAA','WYSTW','WYST', 'TRAW', 'TRAAW', 'SEWAL', 'SERW', 'SERA', 'SERAW', 'ZTRA',  'ZWR', 'SAL', 'BRK', 'KUR', 'REKL', 'SERWD', 'WYSTD', 'ZWS')
                and (";

        $dataSplit = array_chunk($data, 999);
        $dataCount = count($dataSplit);
        $params = [];
        $types = [];

        foreach($dataSplit as $key => $single) {
            $query .= " numer_zamowienia in (:nz_{$key}) ";
            $params["nz_{$key}"] = $single;
            $types["nz_{$key}"] = Connection::PARAM_STR_ARRAY;

            if($key < $dataCount - 1){
                $query .= " OR ";
            }
        }

        $query .= ")) d";

        return $this->oracleConn->executeQuery($query, $params, $types)->fetchAllAssociative();
    }


    //////////Tu Krzysia\\\\\\\\\\\\\\

    commande.fraisport + (CASE
        WHEN COALESCE(commande.totht, 0) <> 0 THEN ROUND(commande.totht, 2)
        WHEN COALESCE(commande.prixvtht, 0) <> 0 THEN ROUND(commande.prixvtht, 2)
        ELSE ROUND(cde.totmatiere, 2)
    END) * commande.pcport/100
 

Obsługa handlowa



commande.supplfft + (CASE
       WHEN COALESCE(commande.totht, 0) <> 0 THEN ROUND(commande.totht, 2)
       WHEN COALESCE(commande.prixvtht, 0) <> 0 THEN ROUND(commande.prixvtht, 2)
       ELSE ROUND(cde.totmatiere, 2)
   END) * commande.supplpc/100
 

Żeby działało, to trzeba zrobić join do commande i wybrać typedoc=X jak poniżej



LEFT JOIN cde cde ON cde.commande=commande.numero AND cde.typedoc = 'X'
