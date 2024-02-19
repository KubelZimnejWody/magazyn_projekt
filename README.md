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

   //////////Tu drugie od Szaraty (nie impuls)\\\\\\\\\\\\\\
$query = "SELECT 
                    ec.`KOD_WIN_PRO` as '1',
                    '' as '2',
                    ''  as '3',
                    wc.`date`  as '4',
                    wc.`dlivraison`  as '5',
                    '' as '6',
                    ec.`WARTOSC_NETTO_PLN`  as '7',
                    '' as '8',
                    ec.`NUMER_FAKTURY`  as '9',
                    ''  as '10',
                    REPLACE(FORMAT(wc.`totht`,2,'pl_PL'),',','.')  as '11',
                    wc.`tarif`  as '12' ,
                    REPLACE(IF(ec.`NUMER_ZAMOWIENIA_WG_KLIENTA` IS NULL or ec.`NUMER_ZAMOWIENIA_WG_KLIENTA` = '', '-', ec.`NUMER_ZAMOWIENIA_WG_KLIENTA`),'=','') as '13',
                    trim(wc.`repres`) as '14',
                    '' as '15',
                    '' as '16',
                    '' as '17',
                    '' as '18',
                    '' as '19',
                    '' as '20'
                    FROM ekosystem.commission ec 
                    left join winprox.commande wc on ec.NUMER_ZAMOWIENIA = wc.numero 
                    left join winprox.client wcli on wcli.code = wc.client 
                    where ( wc.`dlivraison`  BETWEEN :od AND :do ) 
                    and SUBSTRING( wc.numero,1,POSITION('/' IN wc.numero)-1) not in ('TRA', 'SEW', 'TRAA', 'TRAAL', 'WYSTW', 'WYST', 'TRAW', 'TRAAW', 'SEWAL', 'SERW', 'SERA', 'SERAW', 'ZTRA', 'ZWR', 'SAL','SERAL', 'OKL','OF', 'OFAL','WYSTAL', 'BRK', 'KUR', 'REKL', 'SERWD', 'WYSTD', 'ZWS')
                   ";
               
        
        $query2="SELECT  
                    wc.`client` as '1',
                    '' as '2',
                    ec.`CREATED_DATE` as '3',
                    wcli.`pays` as '4',
                    wc.`numero` as '5',
                    wc.`date` as '6',
                    wc.`dlivraison`  as '7',
                    ec.`DATA_WYSTAWIENIA_FAKTURY` as '8',
                    ec.`NUMER_FAKTURY`  as '9',
                    ec.`WARTOSC_ZK_WAL` as '10',
                    '' as '11',
                    wd.`code` as '12',
                    '' as '13',
                    '' as '14',
                    trim(wc.`repres`) as '15',
                    '' as '16',
                    wc.`tarif`  as '17',
                    REPLACE(FORMAT(wc.`totht`,2,'pl_PL'),',','.') as '18',
                    '' as '19',
                    '' as '20'
                    FROM winprox.commande wc  
                    left join ekosystem.commission ec on ec.NUMER_ZAMOWIENIA = wc.numero 
                    left join winprox.client wcli on wcli.code = wc.client 
                    left join winprox.devise wd on wd.code = wcli.devise 
                    where ( wc.`dlivraison`  BETWEEN :od AND :do )
                     and SUBSTRING( numero,1,POSITION('/' IN numero)-1) not in ('TRA', 'SEW', 'TRAA', 'TRAAL', 'WYSTW', 'WYST', 'TRAW', 'TRAAW', 'SEWAL', 'SERW', 'SERA', 'SERAW', 'ZTRA', 'ZWR', 'SAL', 'SERAL', 'OKL', 'OF', 'OFAL', 'WYSTAL', 'BRK', 'KUR', 'REKL', 'SERWD', 'WYSTD', 'ZWS')
                     and numero not in (select NUMER_ZAMOWIENIA from ekosystem.commission )
                    ";
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
