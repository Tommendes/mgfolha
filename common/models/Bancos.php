<?php

namespace common\models;

/**
 * @author TomMe
 */
class Bancos {

    /**
     * Retorna os Bancos para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getBancos() {
        $retorno = [
            '1' => 'BCO DO BRASIL S.A.',
//            '3' => 'BCO DA AMAZONIA S.A.',
//            '4' => 'BCO DO NORDESTE DO BRASIL S.A.',
//            '7' => 'BNDES',
//            '10' => 'CREDICOAMO',
//            '11' => 'C.SUISSE HEDGING-GRIFFO CV S/A',
//            '12' => 'BANCO INBURSA',
//            '14' => 'NATIXIS BRASIL S.A. BM',
//            '15' => 'UBS BRASIL CCTVM S.A.',
//            '16' => 'CCM DESP TRÂNS SC E RS',
//            '17' => 'BNY MELLON BCO S.A.',
//            '18' => 'BCO TRICURY S.A.',
//            '21' => 'BCO BANESTES S.A.',
//            '24' => 'BCO BANDEPE S.A.',
//            '25' => 'BCO ALFA S.A.',
//            '29' => 'BANCO ITAÚ CONSIGNADO S.A.',
//            '33' => 'BCO SANTANDER (BRASIL) S.A.',
//            '36' => 'BCO BBI S.A.',
//            '37' => 'BCO DO EST. DO PA S.A.',
//            '40' => 'BCO CARGILL S.A.',
//            '41' => 'BCO DO ESTADO DO RS S.A.',
//            '47' => 'BCO DO EST. DE SE S.A.',
//            '60' => 'CONFIDENCE CC S.A.',
//            '62' => 'HIPERCARD BM S.A.',
//            '63' => 'BANCO BRADESCARD',
//            '64' => 'GOLDMAN SACHS DO BRASIL BM S.A',
//            '65' => 'BCO ANDBANK S.A.',
//            '66' => 'BCO MORGAN STANLEY S.A.',
//            '69' => 'BCO CREFISA S.A.',
//            '70' => 'BRB - BCO DE BRASILIA S.A.',
//            '74' => 'BCO. J.SAFRA S.A.',
//            '75' => 'BCO ABN AMRO S.A.',
//            '76' => 'BCO KDB BRASIL S.A.',
//            '77' => 'BANCO INTER',
//            '78' => 'HAITONG BI DO BRASIL S.A.',
//            '79' => 'BCO ORIGINAL DO AGRO S/A',
//            '80' => 'B&T CC LTDA.',
//            '81' => 'BBN BCO BRASILEIRO DE NEGOCIOS S.A.',
//            '82' => 'BANCO TOPÁZIO S.A.',
//            '83' => 'BCO DA CHINA BRASIL S.A.',
//            '84' => 'UNIPRIME NORTE DO PARANÁ - CC',
//            '85' => 'COOP CENTRAL AILOS',
//            '89' => 'CCR REG MOGIANA',
//            '91' => 'CCCM UNICRED CENTRAL RS',
//            '92' => 'BRK S.A. CFI',
//            '93' => 'PÓLOCRED SCMEPP LTDA.',
//            '94' => 'BANCO FINAXIS',
//            '95' => 'BCO CONFIDENCE DE CÂMBIO S.A.',
//            '96' => 'BCO B3 S.A.',
//            '97' => 'CCC NOROESTE BRASILEIRO LTDA.',
//            '98' => 'CREDIALIANÇA CCR',
//            '99' => 'UNIPRIME CENTRAL CCC LTDA.',
//            '100' => 'PLANNER CV S.A.',
//            '101' => 'RENASCENCA DTVM LTDA',
//            '102' => 'XP INVESTIMENTOS CCTVM S/A',
            '104' => 'CAIXA ECONOMICA FEDERAL',
//            '105' => 'LECCA CFI S.A.',
//            '107' => 'BCO BOCOM BBM S.A.',
//            '108' => 'PORTOCRED S.A. - CFI',
//            '111' => 'OLIVEIRA TRUST DTVM S.A.',
//            '113' => 'MAGLIANO S.A. CCVM',
//            '114' => 'CENTRAL COOPERATIVA DE CRÉDITO NO ESTADO DO ESPÍRITO SANTO',
//            '117' => 'ADVANCED CC LTDA',
//            '118' => 'STANDARD CHARTERED BI S.A.',
//            '119' => 'BCO WESTERN UNION',
//            '120' => 'BCO RODOBENS S.A.',
//            '121' => 'BCO AGIBANK S.A.',
//            '122' => 'BCO BRADESCO BERJ S.A.',
//            '124' => 'BCO WOORI BANK DO BRASIL S.A.',
//            '125' => 'BRASIL PLURAL S.A. BCO.',
//            '126' => 'BR PARTNERS BI',
//            '127' => 'CODEPE CVC S.A.',
//            '128' => 'MS BANK S.A. BCO DE CÂMBIO',
//            '129' => 'UBS BRASIL BI S.A.',
//            '130' => 'CARUANA SCFI',
//            '131' => 'TULLETT PREBON BRASIL CVC LTDA',
//            '132' => 'ICBC DO BRASIL BM S.A.',
//            '133' => 'CRESOL CONFEDERAÇÃO',
//            '134' => 'BGC LIQUIDEZ DTVM LTDA',
//            '136' => 'CONF NAC COOP CENTRAIS UNICRED',
//            '137' => 'MULTIMONEY CC LTDA.',
//            '138' => 'GET MONEY CC LTDA',
//            '139' => 'INTESA SANPAOLO BRASIL S.A. BM',
//            '140' => 'EASYNVEST - TÍTULO CV SA',
//            '142' => 'BROKER BRASIL CC LTDA.',
//            '143' => 'TREVISO CC S.A.',
//            '144' => 'BEXS BCO DE CAMBIO S.A.',
//            '145' => 'LEVYCAM CCV LTDA',
//            '146' => 'GUITTA CC LTDA',
//            '149' => 'FACTA S.A. CFI',
//            '157' => 'ICAP DO BRASIL CTVM LTDA.',
//            '159' => 'CASA CREDITO S.A. SCM',
//            '163' => 'COMMERZBANK BRASIL S.A. - BCO MÚLTIPLO',
//            '169' => 'BCO OLÉ BONSUCESSO CONSIGNADO S.A.',
//            '172' => 'ALBATROSS CCV S.A',
//            '173' => 'BRL TRUST DTVM SA',
//            '174' => 'PERNAMBUCANAS FINANC S.A. CFI',
//            '177' => 'GUIDE',
//            '180' => 'CM CAPITAL MARKETS CCTVM LTDA',
//            '182' => 'DACASA FINANCEIRA S/A - SCFI',
//            '183' => 'SOCRED S.A. SCM',
//            '184' => 'BCO ITAÚ BBA S.A.',
//            '188' => 'ATIVA S.A. INVESTIMENTOS CCTVM',
//            '189' => 'HS FINANCEIRA',
//            '190' => 'SERVICOOP',
//            '191' => 'NOVA FUTURA CTVM LTDA.',
//            '194' => 'PARMETAL DTVM LTDA',
//            '196' => 'FAIR CC S.A.',
//            '197' => 'STONE PAGAMENTOS S.A.',
//            '204' => 'BCO BRADESCO CARTOES S.A.',
//            '208' => 'BANCO BTG PACTUAL S.A.',
//            '212' => 'BANCO ORIGINAL',
//            '213' => 'BCO ARBI S.A.',
//            '217' => 'BANCO JOHN DEERE S.A.',
//            '218' => 'BCO BS2 S.A.',
//            '222' => 'BCO CRÉDIT AGRICOLE BR S.A.',
//            '224' => 'BCO FIBRA S.A.',
//            '233' => 'BANCO CIFRA',
            '237' => 'BCO BRADESCO S.A.',
//            '241' => 'BCO CLASSICO S.A.',
//            '243' => 'BCO MÁXIMA S.A.',
//            '246' => 'BCO ABC BRASIL S.A.',
//            '249' => 'BANCO INVESTCRED UNIBANCO S.A.',
//            '250' => 'BCV',
//            '253' => 'BEXS CC S.A.',
//            '254' => 'PARANA BCO S.A.',
//            '260' => 'NU PAGAMENTOS S.A.',
//            '265' => 'BCO FATOR S.A.',
//            '266' => 'BCO CEDULA S.A.',
//            '268' => 'BARIGUI CH',
//            '269' => 'HSBC BANCO DE INVESTIMENTO',
//            '270' => 'SAGITUR CC LTDA',
//            '271' => 'IB CCTVM LTDA',
//            '272' => 'AGK CC S.A.',
//            '273' => 'CCR DE SÃO MIGUEL DO OESTE',
//            '276' => 'SENFF S.A. - CFI',
//            '278' => 'GENIAL INVESTIMENTOS CVM S.A.',
//            '279' => 'CCR DE PRIMAVERA DO LESTE',
//            '280' => 'AVISTA S.A. CFI',
//            '281' => 'CCR COOPAVEL',
//            '283' => 'RB CAPITAL INVESTIMENTOS DTVM LTDA.',
//            '285' => 'FRENTE CC LTDA.',
//            '286' => 'CCR DE OURO',
//            '288' => 'CAROL DTVM LTDA.',
//            '290' => 'PAGSEGURO',
//            '292' => 'BS2 DTVM S.A.',
//            '293' => 'LASTRO RDV DTVM LTDA',
//            '298' => 'VIPS CC LTDA.',
//            '300' => 'BCO LA NACION ARGENTINA',
//            '301' => 'BPP IP S.A.',
//            '307' => 'TERRA INVESTIMENTOS DTVM',
//            '309' => 'CAMBIONET CC LTDA',
//            '310' => 'VORTX DTVM LTDA.',
//            '318' => 'BCO BMG S.A.',
//            '320' => 'BCO CCB BRASIL S.A.',
//            '341' => 'ITAÚ UNIBANCO S.A.',
//            '366' => 'BCO SOCIETE GENERALE BRASIL',
//            '370' => 'BCO MIZUHO S.A.',
//            '376' => 'BCO J.P. MORGAN S.A.',
//            '389' => 'BCO MERCANTIL DO BRASIL S.A.',
//            '394' => 'BCO BRADESCO FINANC. S.A.',
//            '399' => 'KIRTON BANK',
//            '412' => 'BCO CAPITAL S.A.',
//            '422' => 'BCO SAFRA S.A.',
//            '456' => 'BCO MUFG BRASIL S.A.',
//            '464' => 'BCO SUMITOMO MITSUI BRASIL S.A.',
//            '473' => 'BCO CAIXA GERAL BRASIL S.A.',
//            '477' => 'CITIBANK N.A.',
//            '479' => 'BCO ITAUBANK S.A.',
//            '487' => 'DEUTSCHE BANK S.A.BCO ALEMAO',
//            '488' => 'JPMORGAN CHASE BANK',
//            '492' => 'ING BANK N.V.',
//            '494' => 'BCO REP ORIENTAL URUGUAY BCE',
//            '495' => 'BCO LA PROVINCIA B AIRES BCE',
//            '505' => 'BCO CREDIT SUISSE (BRL) S.A.',
//            '545' => 'SENSO CCVM S.A.',
//            '600' => 'BCO LUSO BRASILEIRO S.A.',
//            '604' => 'BCO INDUSTRIAL DO BRASIL S.A.',
//            '610' => 'BCO VR S.A.',
//            '611' => 'BCO PAULISTA S.A.',
//            '612' => 'BCO GUANABARA S.A.',
//            '613' => 'OMNI BANCO S.A.',
//            '623' => 'BANCO PAN',
//            '626' => 'BCO FICSA S.A.',
//            '630' => 'BCO INTERCAP S.A.',
//            '633' => 'BCO RENDIMENTO S.A.',
//            '634' => 'BCO TRIANGULO S.A.',
//            '637' => 'BCO SOFISA S.A.',
//            '641' => 'BCO ALVORADA S.A.',
//            '643' => 'BCO PINE S.A.',
//            '652' => 'ITAÚ UNIBANCO HOLDING S.A.',
//            '653' => 'BCO INDUSVAL S.A.',
//            '654' => 'BCO A.J. RENNER S.A.',
//            '655' => 'BCO VOTORANTIM S.A.',
//            '707' => 'BCO DAYCOVAL S.A',
//            '712' => 'BCO OURINVEST S.A.',
//            '739' => 'BCO CETELEM S.A.',
//            '741' => 'BCO RIBEIRAO PRETO S.A.',
//            '743' => 'BANCO SEMEAR',
//            '745' => 'BCO CITIBANK S.A.',
//            '746' => 'BCO MODAL S.A.',
//            '747' => 'BCO RABOBANK INTL BRASIL S.A.',
//            '748' => 'BCO COOPERATIVO SICREDI S.A.',
//            '751' => 'SCOTIABANK BRASIL',
//            '752' => 'BCO BNP PARIBAS BRASIL S A',
//            '753' => 'NOVO BCO CONTINENTAL S.A. - BM',
//            '754' => 'BANCO SISTEMA',
//            '755' => 'BOFA MERRILL LYNCH BM S.A.',
//            '756' => 'BANCOOB',
//            '757' => 'BCO KEB HANA DO BRASIL S.A.',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do Banco
     * @return string
     */
    public static function getBanco($id = null) {

        switch ($id) {
            case '1' : $retorno = 'BCO DO BRASIL S.A.';
                break;
//            case '3' : $retorno = 'BCO DA AMAZONIA S.A.';
//                break;
//            case '4' : $retorno = 'BCO DO NORDESTE DO BRASIL S.A.';
//                break;
//            case '7' : $retorno = 'BNDES';
//                break;
//            case '10' : $retorno = 'CREDICOAMO';
//                break;
//            case '11' : $retorno = 'C.SUISSE HEDGING-GRIFFO CV S/A';
//                break;
//            case '12' : $retorno = 'BANCO INBURSA';
//                break;
//            case '14' : $retorno = 'NATIXIS BRASIL S.A. BM';
//                break;
//            case '15' : $retorno = 'UBS BRASIL CCTVM S.A.';
//                break;
//            case '16' : $retorno = 'CCM DESP TRÂNS SC E RS';
//                break;
//            case '17' : $retorno = 'BNY MELLON BCO S.A.';
//                break;
//            case '18' : $retorno = 'BCO TRICURY S.A.';
//                break;
//            case '21' : $retorno = 'BCO BANESTES S.A.';
//                break;
//            case '24' : $retorno = 'BCO BANDEPE S.A.';
//                break;
//            case '25' : $retorno = 'BCO ALFA S.A.';
//                break;
//            case '29' : $retorno = 'BANCO ITAÚ CONSIGNADO S.A.';
//                break;
//            case '33' : $retorno = 'BCO SANTANDER (BRASIL) S.A.';
//                break;
//            case '36' : $retorno = 'BCO BBI S.A.';
//                break;
//            case '37' : $retorno = 'BCO DO EST. DO PA S.A.';
//                break;
//            case '40' : $retorno = 'BCO CARGILL S.A.';
//                break;
//            case '41' : $retorno = 'BCO DO ESTADO DO RS S.A.';
//                break;
//            case '47' : $retorno = 'BCO DO EST. DE SE S.A.';
//                break;
//            case '60' : $retorno = 'CONFIDENCE CC S.A.';
//                break;
//            case '62' : $retorno = 'HIPERCARD BM S.A.';
//                break;
//            case '63' : $retorno = 'BANCO BRADESCARD';
//                break;
//            case '64' : $retorno = 'GOLDMAN SACHS DO BRASIL BM S.A';
//                break;
//            case '65' : $retorno = 'BCO ANDBANK S.A.';
//                break;
//            case '66' : $retorno = 'BCO MORGAN STANLEY S.A.';
//                break;
//            case '69' : $retorno = 'BCO CREFISA S.A.';
//                break;
//            case '70' : $retorno = 'BRB - BCO DE BRASILIA S.A.';
//                break;
//            case '74' : $retorno = 'BCO. J.SAFRA S.A.';
//                break;
//            case '75' : $retorno = 'BCO ABN AMRO S.A.';
//                break;
//            case '76' : $retorno = 'BCO KDB BRASIL S.A.';
//                break;
//            case '77' : $retorno = 'BANCO INTER';
//                break;
//            case '78' : $retorno = 'HAITONG BI DO BRASIL S.A.';
//                break;
//            case '79' : $retorno = 'BCO ORIGINAL DO AGRO S/A';
//                break;
//            case '80' : $retorno = 'B&T CC LTDA.';
//                break;
//            case '81' : $retorno = 'BBN BCO BRASILEIRO DE NEGOCIOS S.A.';
//                break;
//            case '82' : $retorno = 'BANCO TOPÁZIO S.A.';
//                break;
//            case '83' : $retorno = 'BCO DA CHINA BRASIL S.A.';
//                break;
//            case '84' : $retorno = 'UNIPRIME NORTE DO PARANÁ - CC';
//                break;
//            case '85' : $retorno = 'COOP CENTRAL AILOS';
//                break;
//            case '89' : $retorno = 'CCR REG MOGIANA';
//                break;
//            case '91' : $retorno = 'CCCM UNICRED CENTRAL RS';
//                break;
//            case '92' : $retorno = 'BRK S.A. CFI';
//                break;
//            case '93' : $retorno = 'PÓLOCRED SCMEPP LTDA.';
//                break;
//            case '94' : $retorno = 'BANCO FINAXIS';
//                break;
//            case '95' : $retorno = 'BCO CONFIDENCE DE CÂMBIO S.A.';
//                break;
//            case '96' : $retorno = 'BCO B3 S.A.';
//                break;
//            case '97' : $retorno = 'CCC NOROESTE BRASILEIRO LTDA.';
//                break;
//            case '98' : $retorno = 'CREDIALIANÇA CCR';
//                break;
//            case '99' : $retorno = 'UNIPRIME CENTRAL CCC LTDA.';
//                break;
//            case '100' : $retorno = 'PLANNER CV S.A.';
//                break;
//            case '101' : $retorno = 'RENASCENCA DTVM LTDA';
//                break;
//            case '102' : $retorno = 'XP INVESTIMENTOS CCTVM S/A';
//                break;
            case '104' : $retorno = 'CAIXA ECONOMICA FEDERAL';
                break;
//            case '105' : $retorno = 'LECCA CFI S.A.';
//                break;
//            case '107' : $retorno = 'BCO BOCOM BBM S.A.';
//                break;
//            case '108' : $retorno = 'PORTOCRED S.A. - CFI';
//                break;
//            case '111' : $retorno = 'OLIVEIRA TRUST DTVM S.A.';
//                break;
//            case '113' : $retorno = 'MAGLIANO S.A. CCVM';
//                break;
//            case '114' : $retorno = 'CENTRAL COOPERATIVA DE CRÉDITO NO ESTADO DO ESPÍRITO SANTO';
//                break;
//            case '117' : $retorno = 'ADVANCED CC LTDA';
//                break;
//            case '118' : $retorno = 'STANDARD CHARTERED BI S.A.';
//                break;
//            case '119' : $retorno = 'BCO WESTERN UNION';
//                break;
//            case '120' : $retorno = 'BCO RODOBENS S.A.';
//                break;
//            case '121' : $retorno = 'BCO AGIBANK S.A.';
//                break;
//            case '122' : $retorno = 'BCO BRADESCO BERJ S.A.';
//                break;
//            case '124' : $retorno = 'BCO WOORI BANK DO BRASIL S.A.';
//                break;
//            case '125' : $retorno = 'BRASIL PLURAL S.A. BCO.';
//                break;
//            case '126' : $retorno = 'BR PARTNERS BI';
//                break;
//            case '127' : $retorno = 'CODEPE CVC S.A.';
//                break;
//            case '128' : $retorno = 'MS BANK S.A. BCO DE CÂMBIO';
//                break;
//            case '129' : $retorno = 'UBS BRASIL BI S.A.';
//                break;
//            case '130' : $retorno = 'CARUANA SCFI';
//                break;
//            case '131' : $retorno = 'TULLETT PREBON BRASIL CVC LTDA';
//                break;
//            case '132' : $retorno = 'ICBC DO BRASIL BM S.A.';
//                break;
//            case '133' : $retorno = 'CRESOL CONFEDERAÇÃO';
//                break;
//            case '134' : $retorno = 'BGC LIQUIDEZ DTVM LTDA';
//                break;
//            case '136' : $retorno = 'CONF NAC COOP CENTRAIS UNICRED';
//                break;
//            case '137' : $retorno = 'MULTIMONEY CC LTDA.';
//                break;
//            case '138' : $retorno = 'GET MONEY CC LTDA';
//                break;
//            case '139' : $retorno = 'INTESA SANPAOLO BRASIL S.A. BM';
//                break;
//            case '140' : $retorno = 'EASYNVEST - TÍTULO CV SA';
//                break;
//            case '142' : $retorno = 'BROKER BRASIL CC LTDA.';
//                break;
//            case '143' : $retorno = 'TREVISO CC S.A.';
//                break;
//            case '144' : $retorno = 'BEXS BCO DE CAMBIO S.A.';
//                break;
//            case '145' : $retorno = 'LEVYCAM CCV LTDA';
//                break;
//            case '146' : $retorno = 'GUITTA CC LTDA';
//                break;
//            case '149' : $retorno = 'FACTA S.A. CFI';
//                break;
//            case '157' : $retorno = 'ICAP DO BRASIL CTVM LTDA.';
//                break;
//            case '159' : $retorno = 'CASA CREDITO S.A. SCM';
//                break;
//            case '163' : $retorno = 'COMMERZBANK BRASIL S.A. - BCO MÚLTIPLO';
//                break;
//            case '169' : $retorno = 'BCO OLÉ BONSUCESSO CONSIGNADO S.A.';
//                break;
//            case '172' : $retorno = 'ALBATROSS CCV S.A';
//                break;
//            case '173' : $retorno = 'BRL TRUST DTVM SA';
//                break;
//            case '174' : $retorno = 'PERNAMBUCANAS FINANC S.A. CFI';
//                break;
//            case '177' : $retorno = 'GUIDE';
//                break;
//            case '180' : $retorno = 'CM CAPITAL MARKETS CCTVM LTDA';
//                break;
//            case '182' : $retorno = 'DACASA FINANCEIRA S/A - SCFI';
//                break;
//            case '183' : $retorno = 'SOCRED S.A. SCM';
//                break;
//            case '184' : $retorno = 'BCO ITAÚ BBA S.A.';
//                break;
//            case '188' : $retorno = 'ATIVA S.A. INVESTIMENTOS CCTVM';
//                break;
//            case '189' : $retorno = 'HS FINANCEIRA';
//                break;
//            case '190' : $retorno = 'SERVICOOP';
//                break;
//            case '191' : $retorno = 'NOVA FUTURA CTVM LTDA.';
//                break;
//            case '194' : $retorno = 'PARMETAL DTVM LTDA';
//                break;
//            case '196' : $retorno = 'FAIR CC S.A.';
//                break;
//            case '197' : $retorno = 'STONE PAGAMENTOS S.A.';
//                break;
//            case '204' : $retorno = 'BCO BRADESCO CARTOES S.A.';
//                break;
//            case '208' : $retorno = 'BANCO BTG PACTUAL S.A.';
//                break;
//            case '212' : $retorno = 'BANCO ORIGINAL';
//                break;
//            case '213' : $retorno = 'BCO ARBI S.A.';
//                break;
//            case '217' : $retorno = 'BANCO JOHN DEERE S.A.';
//                break;
//            case '218' : $retorno = 'BCO BS2 S.A.';
//                break;
//            case '222' : $retorno = 'BCO CRÉDIT AGRICOLE BR S.A.';
//                break;
//            case '224' : $retorno = 'BCO FIBRA S.A.';
//                break;
//            case '233' : $retorno = 'BANCO CIFRA';
//                break;
            case '237' : $retorno = 'BCO BRADESCO S.A.';
                break;
//            case '241' : $retorno = 'BCO CLASSICO S.A.';
//                break;
//            case '243' : $retorno = 'BCO MÁXIMA S.A.';
//                break;
//            case '246' : $retorno = 'BCO ABC BRASIL S.A.';
//                break;
//            case '249' : $retorno = 'BANCO INVESTCRED UNIBANCO S.A.';
//                break;
//            case '250' : $retorno = 'BCV';
//                break;
//            case '253' : $retorno = 'BEXS CC S.A.';
//                break;
//            case '254' : $retorno = 'PARANA BCO S.A.';
//                break;
//            case '260' : $retorno = 'NU PAGAMENTOS S.A.';
//                break;
//            case '265' : $retorno = 'BCO FATOR S.A.';
//                break;
//            case '266' : $retorno = 'BCO CEDULA S.A.';
//                break;
//            case '268' : $retorno = 'BARIGUI CH';
//                break;
//            case '269' : $retorno = 'HSBC BANCO DE INVESTIMENTO';
//                break;
//            case '270' : $retorno = 'SAGITUR CC LTDA';
//                break;
//            case '271' : $retorno = 'IB CCTVM LTDA';
//                break;
//            case '272' : $retorno = 'AGK CC S.A.';
//                break;
//            case '273' : $retorno = 'CCR DE SÃO MIGUEL DO OESTE';
//                break;
//            case '276' : $retorno = 'SENFF S.A. - CFI';
//                break;
//            case '278' : $retorno = 'GENIAL INVESTIMENTOS CVM S.A.';
//                break;
//            case '279' : $retorno = 'CCR DE PRIMAVERA DO LESTE';
//                break;
//            case '280' : $retorno = 'AVISTA S.A. CFI';
//                break;
//            case '281' : $retorno = 'CCR COOPAVEL';
//                break;
//            case '283' : $retorno = 'RB CAPITAL INVESTIMENTOS DTVM LTDA.';
//                break;
//            case '285' : $retorno = 'FRENTE CC LTDA.';
//                break;
//            case '286' : $retorno = 'CCR DE OURO';
//                break;
//            case '288' : $retorno = 'CAROL DTVM LTDA.';
//                break;
//            case '290' : $retorno = 'PAGSEGURO';
//                break;
//            case '292' : $retorno = 'BS2 DTVM S.A.';
//                break;
//            case '293' : $retorno = 'LASTRO RDV DTVM LTDA';
//                break;
//            case '298' : $retorno = 'VIPS CC LTDA.';
//                break;
//            case '300' : $retorno = 'BCO LA NACION ARGENTINA';
//                break;
//            case '301' : $retorno = 'BPP IP S.A.';
//                break;
//            case '307' : $retorno = 'TERRA INVESTIMENTOS DTVM';
//                break;
//            case '309' : $retorno = 'CAMBIONET CC LTDA';
//                break;
//            case '310' : $retorno = 'VORTX DTVM LTDA.';
//                break;
//            case '318' : $retorno = 'BCO BMG S.A.';
//                break;
//            case '320' : $retorno = 'BCO CCB BRASIL S.A.';
//                break;
//            case '341' : $retorno = 'ITAÚ UNIBANCO S.A.';
//                break;
//            case '366' : $retorno = 'BCO SOCIETE GENERALE BRASIL';
//                break;
//            case '370' : $retorno = 'BCO MIZUHO S.A.';
//                break;
//            case '376' : $retorno = 'BCO J.P. MORGAN S.A.';
//                break;
//            case '389' : $retorno = 'BCO MERCANTIL DO BRASIL S.A.';
//                break;
//            case '394' : $retorno = 'BCO BRADESCO FINANC. S.A.';
//                break;
//            case '399' : $retorno = 'KIRTON BANK';
//                break;
//            case '412' : $retorno = 'BCO CAPITAL S.A.';
//                break;
//            case '422' : $retorno = 'BCO SAFRA S.A.';
//                break;
//            case '456' : $retorno = 'BCO MUFG BRASIL S.A.';
//                break;
//            case '464' : $retorno = 'BCO SUMITOMO MITSUI BRASIL S.A.';
//                break;
//            case '473' : $retorno = 'BCO CAIXA GERAL BRASIL S.A.';
//                break;
//            case '477' : $retorno = 'CITIBANK N.A.';
//                break;
//            case '479' : $retorno = 'BCO ITAUBANK S.A.';
//                break;
//            case '487' : $retorno = 'DEUTSCHE BANK S.A.BCO ALEMAO';
//                break;
//            case '488' : $retorno = 'JPMORGAN CHASE BANK';
//                break;
//            case '492' : $retorno = 'ING BANK N.V.';
//                break;
//            case '494' : $retorno = 'BCO REP ORIENTAL URUGUAY BCE';
//                break;
//            case '495' : $retorno = 'BCO LA PROVINCIA B AIRES BCE';
//                break;
//            case '505' : $retorno = 'BCO CREDIT SUISSE (BRL) S.A.';
//                break;
//            case '545' : $retorno = 'SENSO CCVM S.A.';
//                break;
//            case '600' : $retorno = 'BCO LUSO BRASILEIRO S.A.';
//                break;
//            case '604' : $retorno = 'BCO INDUSTRIAL DO BRASIL S.A.';
//                break;
//            case '610' : $retorno = 'BCO VR S.A.';
//                break;
//            case '611' : $retorno = 'BCO PAULISTA S.A.';
//                break;
//            case '612' : $retorno = 'BCO GUANABARA S.A.';
//                break;
//            case '613' : $retorno = 'OMNI BANCO S.A.';
//                break;
//            case '623' : $retorno = 'BANCO PAN';
//                break;
//            case '626' : $retorno = 'BCO FICSA S.A.';
//                break;
//            case '630' : $retorno = 'BCO INTERCAP S.A.';
//                break;
//            case '633' : $retorno = 'BCO RENDIMENTO S.A.';
//                break;
//            case '634' : $retorno = 'BCO TRIANGULO S.A.';
//                break;
//            case '637' : $retorno = 'BCO SOFISA S.A.';
//                break;
//            case '641' : $retorno = 'BCO ALVORADA S.A.';
//                break;
//            case '643' : $retorno = 'BCO PINE S.A.';
//                break;
//            case '652' : $retorno = 'ITAÚ UNIBANCO HOLDING S.A.';
//                break;
//            case '653' : $retorno = 'BCO INDUSVAL S.A.';
//                break;
//            case '654' : $retorno = 'BCO A.J. RENNER S.A.';
//                break;
//            case '655' : $retorno = 'BCO VOTORANTIM S.A.';
//                break;
//            case '707' : $retorno = 'BCO DAYCOVAL S.A';
//                break;
//            case '712' : $retorno = 'BCO OURINVEST S.A.';
//                break;
//            case '739' : $retorno = 'BCO CETELEM S.A.';
//                break;
//            case '741' : $retorno = 'BCO RIBEIRAO PRETO S.A.';
//                break;
//            case '743' : $retorno = 'BANCO SEMEAR';
//                break;
//            case '745' : $retorno = 'BCO CITIBANK S.A.';
//                break;
//            case '746' : $retorno = 'BCO MODAL S.A.';
//                break;
//            case '747' : $retorno = 'BCO RABOBANK INTL BRASIL S.A.';
//                break;
//            case '748' : $retorno = 'BCO COOPERATIVO SICREDI S.A.';
//                break;
//            case '751' : $retorno = 'SCOTIABANK BRASIL';
//                break;
//            case '752' : $retorno = 'BCO BNP PARIBAS BRASIL S A';
//                break;
//            case '753' : $retorno = 'NOVO BCO CONTINENTAL S.A. - BM';
//                break;
//            case '754' : $retorno = 'BANCO SISTEMA';
//                break;
//            case '755' : $retorno = 'BOFA MERRILL LYNCH BM S.A.';
//                break;
//            case '756' : $retorno = 'BANCOOB';
//                break;
//            case '757' : $retorno = 'BCO KEB HANA DO BRASIL S.A.';
//                break;

            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

}
