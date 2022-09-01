<?php

namespace common\models;

use yii\helpers\Json;

/**
 * Retorna as listas que configuram os dados das tabelas do sistema
 * #listas:
 * # Vinculos
 * # Escolaridades
 * # Tipos de dependencia
 *
 * @author TomMe
 */
class Listas {

    /**
     * Retorna os tipos sim e não para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getSNs() {
        $retorno = [
            '0' => 'Não',
            '1' => 'Sim',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da faixa SN
     * @return string
     */
    public static function getSN($id = null) {

        switch ($id) {
            case '0' : $retorno = 'Não';
                break;
            case '1' : $retorno = 'Sim';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos sim e não para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCatRFBs() {
        $retorno = [
            '0561' => '(0561)IRRF - RENDIMENTO DO TRABALHO ASSALARIADO',
        ];
        return $retorno;
    }

    /**
     * Retorna o label da faixa SN
     * @return string
     */
    public static function getCatRFB($id = null) {

        switch ($id) {
            case '561' : $retorno = '(0561)IRRF - RENDIMENTO DO TRABALHO ASSALARIADO';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de nomeação para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getNomeacoes() {
        $retorno = [
            'prompt' => 'Selecione...',
            '1' => '1 - LEI',
            '2' => '2 - DECRETO',
            '3' => '3 - PORTARIA',
            '4' => '4 - CONTRATO',
            '9' => '9 - OUTROS',
        ];

        return $retorno;
    }

    /**
     * Retorna o label do tipo de nomeação
     * @return string
     */
    public static function getNomeacao($id = null) {

        switch ($id) {
            case '1' : $retorno = 'LEI';
                break;
            case '2' : $retorno = 'DECRETO';
                break;
            case '3' : $retorno = 'PORTARIA';
                break;
            case '4' : $retorno = 'CONTRATO';
                break;
            case '9' : $retorno = 'OUTROS';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as categorias Sefip para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCatsSefip() {
        $retorno = [
            '01' => '01 - EMPREGADO',
            '02' => '02 - TRABALHADOR AVULSO',
            '03' => '03 - TRABALHADOR NÃO VINCULADO AO RGPS, MAS COM DIREITO AO FGTS ',
            '04' => '04 - EMPREGADO SOB CONTRATO DE TRABALHO POR PRAZO DETERMINADO',
            '05' => '05 - CONTRIBUINTE INDIVIDUAL - DIRETOR NÃO EMPREGADO COM FGTS (LEI Nº 8.036/90, ART. 16)',
            '06' => '06 - EMPREGADO DOMÉSTICO',
            '07' => '07 - MENOR APRENDIZ – LEI Nº 11.180/2005	',
            '11' => '11 - CONTRIBUINTE INDIVIDUAL - DIRETOR NÃO EMPREGADO E DEMAIS EMPRESÁRIOS SEM FGTS',
            '12' => '12 - DEMAIS AGENTES PÚBLICOS',
            '13' => '13 - CONTRIBUINTE INDIVIDUAL – TRABALHADOR AUTÔNOMO OU A ESTE EQUIPARADO, INCLUSIVE O OPERADOR DE MÁQUINA, COM CONTRIBUIÇÃO SOBRE REMUNERAÇÃO; TRABALHADOR ASSOCIADO À COOPERATIVA DE PRODUÇÃO',
            '14' => '14 - CONTRIBUINTE INDIVIDUAL – TRABALHADOR AUTÔNOMO OU A ESTE EQUIPARADO, INCLUSIVE O OPERADOR DE MÁQUINA, COM CONTRIBUIÇÃO SOBRE SALÁRIO-BASE',
            '15' => '15 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO, COM CONTRIBUIÇÃO SOBRE REMUNERAÇÃO',
            '16' => '16 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO, COM CONTRIBUIÇÃO SOBRE SALÁRIO-BASE',
            '17' => '17 - CONTRIBUINTE INDIVIDUAL – COOPERADO QUE PRESTA SERVIÇOS A EMPRESAS CONTRATANTES DA COOPERATIVA DE TRABALHO',
            '18' => '18 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR COOPERADO QUE PRESTA SERVIÇOS A EMPRESAS CONTRATANTES DA COOPERATIVA DE TRABALHO',
            '19' => '19 - AGENTE POLÍTICO',
            '20' => '20 - SERVIDOR PÚBLICO OCUPANTE, EXCLUSIVAMENTE, DE CARGO EM COMISSÃO, SERVIDOR PÚBLICO OCUPANTE DE CARGO TEMPORÁRIO',
            '21' => '21 - SERVIDOR PÚBLICO TITULAR DE CARGO EFETIVO, MAGISTRADO, MEMBRO DO MINISTÉRIO PÚBLICO E DO TRIBUNAL E CONSELHO DE CONTAS',
            '22' => '22 - CONTRIBUINTE INDIVIDUAL – CONTRATADO POR OUTRO CONTRIBUINTE INDIVIDUAL EQUIPARADO A EMPRESA OU POR PRODUTOR RURAL PESSOA FÍSICA OU POR MISSÃO DIPLOMÁTICA E REPARTIÇÃO CONSULAR DE CARREIRA ESTRANGEIRAS',
            '23' => '23 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO CONTRATADO POR OUTRO CONTRIBUINTE INDIVIDUAL EQUIPARADO À EMPRESA OU POR PRODUTOR RURAL PESSOA FÍSICA OU POR MISSÃO DIPLOMÁTICA E REPARTIÇÃO CONSULAR DE CARREIRA ESTRANGEIRAS',
            '24' => '24 - CONTRIBUINTE INDIVIDUAL – COOPERADO QUE PRESTA SERVIÇOS A ENTIDADE BENEFICENTE DE ASSISTÊNCIA SOCIAL ISENTA DA COTA PATRONAL OU A PESSOA FÍSICA, POR INTERMÉDIO DA COOPERATIVA DE TRABALHO',
            '25' => '25 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR COOPERADO QUE PRESTA SERVIÇOS A ENTIDADE BENEFICENTE DE ASSISTÊNCIA SOCIAL ISENTA DA COTA PATRONAL OU A PESSOA FÍSICA, POR INTERMÉDIO DA COOPERATIVA DE TRABALHO',
            '26' => '26 - DIRIGENTE SINDICAL, EM RELAÇÃO AO ADICIONAL PAGO PELO SINDICATO; MAGISTRADO CLASSISTA TEMPORÁRIO DA JUSTIÇA DO TRABALHO; MAGISTRADO DOS TRIBUNAIS ELEITORAIS, QUANDO, NAS TRÊS SITUAÇÕES, FOR MANTIDA A QUALIDADE DE SEGURADO EMPREGADO (SEM FGTS)',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da categoria Sefip
     * @return string
     */
    public static function getCatSefip($id = null) {

        switch ($id) {
            case '01' : $retorno = '01 - EMPREGADO';
                break;
            case '02' : $retorno = '02 - TRABALHADOR AVULSO';
                break;
            case '03' : $retorno = '03 - TRABALHADOR NÃO VINCULADO AO RGPS, MAS COM DIREITO AO FGTS ';
                break;
            case '04' : $retorno = '04 - EMPREGADO SOB CONTRATO DE TRABALHO POR PRAZO DETERMINADO';
                break;
            case '05' : $retorno = '05 - CONTRIBUINTE INDIVIDUAL - DIRETOR NÃO EMPREGADO COM FGTS (LEI Nº 8.036/90, ART. 16)';
                break;
            case '06' : $retorno = '06 - EMPREGADO DOMÉSTICO';
                break;
            case '07' : $retorno = '07 - MENOR APRENDIZ – LEI Nº 11.180/2005	';
                break;
            case '11' : $retorno = '11 - CONTRIBUINTE INDIVIDUAL - DIRETOR NÃO EMPREGADO E DEMAIS EMPRESÁRIOS SEM FGTS';
                break;
            case '12' : $retorno = '12 - DEMAIS AGENTES PÚBLICOS';
                break;
            case '13' : $retorno = '13 - CONTRIBUINTE INDIVIDUAL – TRABALHADOR AUTÔNOMO OU A ESTE EQUIPARADO, INCLUSIVE O OPERADOR DE MÁQUINA, COM CONTRIBUIÇÃO SOBRE REMUNERAÇÃO; TRABALHADOR ASSOCIADO À COOPERATIVA DE PRODUÇÃO';
                break;
            case '14' : $retorno = '14 - CONTRIBUINTE INDIVIDUAL – TRABALHADOR AUTÔNOMO OU A ESTE EQUIPARADO, INCLUSIVE O OPERADOR DE MÁQUINA, COM CONTRIBUIÇÃO SOBRE SALÁRIO-BASE';
                break;
            case '15' : $retorno = '15 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO, COM CONTRIBUIÇÃO SOBRE REMUNERAÇÃO';
                break;
            case '16' : $retorno = '16 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO, COM CONTRIBUIÇÃO SOBRE SALÁRIO-BASE';
                break;
            case '17' : $retorno = '17 - CONTRIBUINTE INDIVIDUAL – COOPERADO QUE PRESTA SERVIÇOS A EMPRESAS CONTRATANTES DA COOPERATIVA DE TRABALHO';
                break;
            case '18' : $retorno = '18 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR COOPERADO QUE PRESTA SERVIÇOS A EMPRESAS CONTRATANTES DA COOPERATIVA DE TRABALHO';
                break;
            case '19' : $retorno = '19 - AGENTE POLÍTICO';
                break;
            case '20' : $retorno = '20 - SERVIDOR PÚBLICO OCUPANTE, EXCLUSIVAMENTE, DE CARGO EM COMISSÃO, SERVIDOR PÚBLICO OCUPANTE DE CARGO TEMPORÁRIO';
                break;
            case '21' : $retorno = '21 - SERVIDOR PÚBLICO TITULAR DE CARGO EFETIVO, MAGISTRADO, MEMBRO DO MINISTÉRIO PÚBLICO E DO TRIBUNAL E CONSELHO DE CONTAS';
                break;
            case '22' : $retorno = '22 - CONTRIBUINTE INDIVIDUAL – CONTRATADO POR OUTRO CONTRIBUINTE INDIVIDUAL EQUIPARADO A EMPRESA OU POR PRODUTOR RURAL PESSOA FÍSICA OU POR MISSÃO DIPLOMÁTICA E REPARTIÇÃO CONSULAR DE CARREIRA ESTRANGEIRAS';
                break;
            case '23' : $retorno = '23 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR AUTÔNOMO CONTRATADO POR OUTRO CONTRIBUINTE INDIVIDUAL EQUIPARADO À EMPRESA OU POR PRODUTOR RURAL PESSOA FÍSICA OU POR MISSÃO DIPLOMÁTICA E REPARTIÇÃO CONSULAR DE CARREIRA ESTRANGEIRAS';
                break;
            case '24' : $retorno = '24 - CONTRIBUINTE INDIVIDUAL – COOPERADO QUE PRESTA SERVIÇOS A ENTIDADE BENEFICENTE DE ASSISTÊNCIA SOCIAL ISENTA DA COTA PATRONAL OU A PESSOA FÍSICA, POR INTERMÉDIO DA COOPERATIVA DE TRABALHO';
                break;
            case '25' : $retorno = '25 - CONTRIBUINTE INDIVIDUAL – TRANSPORTADOR COOPERADO QUE PRESTA SERVIÇOS A ENTIDADE BENEFICENTE DE ASSISTÊNCIA SOCIAL ISENTA DA COTA PATRONAL OU A PESSOA FÍSICA, POR INTERMÉDIO DA COOPERATIVA DE TRABALHO';
                break;
            case '26' : $retorno = '26 - DIRIGENTE SINDICAL, EM RELAÇÃO AO ADICIONAL PAGO PELO SINDICATO; MAGISTRADO CLASSISTA TEMPORÁRIO DA JUSTIÇA DO TRABALHO; MAGISTRADO DOS TRIBUNAIS ELEITORAIS, QUANDO, NAS TRÊS SITUAÇÕES, FOR MANTIDA A QUALIDADE DE SEGURADO EMPREGADO (SEM FGTS)';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de certidão para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCertidaoTipos() {
        $retorno = [
            0 => 'Nascimento',
            1 => 'Casamento',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da certidão
     * @return string
     */
    public static function getCertidaoTipo($id = null) {

        switch ($id) {
            case 0 : $retorno = 'Nascimento';
                break;
            case 1 : $retorno = 'Casamento';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de ocorrência para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getOcorrencias() {
        $retorno = [
            null => 'Sem ocorrências',
            '01' => '01 - NÃO EXPOSIÇÃO A AGENTE NOCIVO',
            '02' => '02 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 15 ANOS DE TRABALHO )',
            '03' => '03 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL 20 ANOS DE TRABALHO )',
            '04' => '04 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL 25 ANOS DE TRABALHO )',
            '05' => '05 – MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - NÃO EXPOSIÇÃO A AGENTE NOCIVO',
            '06' => '06 – MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 15 ANOS DE TRABALHO )',
            '07' => '07 - MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 20 ANOS DE TRABALHO )',
            '08' => '08 - MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 25 ANOS DE TRABALHO )',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da cocorrência
     * @return string
     */
    public static function getOcorrencia($id = null) {

        switch ($id) {
            case '01' : $retorno = '01 - NÃO EXPOSIÇÃO A AGENTE NOCIVO';
                break;
            case '02' : $retorno = '02 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 15 ANOS DE TRABALHO )';
                break;
            case '03' : $retorno = '03 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL 20 ANOS DE TRABALHO )';
                break;
            case '04' : $retorno = '04 - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL 25 ANOS DE TRABALHO )';
                break;
            case '05' : $retorno = '05 – MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - NÃO EXPOSIÇÃO A AGENTE NOCIVO';
                break;
            case '06' : $retorno = '06 – MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 15 ANOS DE TRABALHO )';
                break;
            case '07' : $retorno = '07 - MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 20 ANOS DE TRABALHO )';
                break;
            case '08' : $retorno = '08 - MAIS DE UM VÍNCULO EMPREGATÍCIO ( OU FONTE PAGADORA ) - EXPOSIÇÃO A AGENTE NOCIVO ( APOSENTADORIA ESPECIAL AOS 25 ANOS DE TRABALHO )';
                break;

            default: $retorno = '<span class="not-set">(Sem ocorrências)</span>';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de faixas para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getTiposFaixa($retorno = 'total') {
        switch ($retorno) {
            case 'salario' :
                $retorno = [
                    '2' => 'Salário família RPPS',
                    '3' => 'Salário família INSS',
                ];
                break;
            case 'previdencia' :
                $retorno = [
                    '0' => 'INSS',
                    '4' => 'RPPS',
                ];
                break;
            default :
                $retorno = [
                    '0' => 'INSS',
                    '1' => 'IRRF',
                    '2' => 'Salário família RPPS',
                    '3' => 'Salário família INSS',
                    '4' => 'RPPS',
                ];
                break;
        }

        return $retorno;
    }

    /**
     * Retorna o label da faixa para a tabela fin_faixas
     * @return string
     */
    public static function getTipoFaixa($id = null) {

        switch ($id) {
            case '0' : $retorno = 'INSS';
                break;
            case '1' : $retorno = 'IRRF';
                break;
            case '2' : $retorno = 'Salário família RPPS';
                break;
            case '3' : $retorno = 'Salário família INSS';
                break;
            case '4' : $retorno = 'RPPS';
                break;
            default: $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as situações funcionais para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getSituacoesCadastrais() {
        $retorno = [
            '0' => 'Pré cadastro(não gera folha)',
            '1' => 'Admitido',
            '2' => 'Demitido',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da tipo funcional
     * @return string
     */
    public static function getSituacaoCadastral($id = null) {
        switch ($id) {
            case '0' : $retorno = 'Pré cadastro(não gera folha)';
                break;
            case '1' : $retorno = 'Admitido';
                break;
            case '2' : $retorno = 'Demitido';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as situações funcionais para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getSituacoesFuncionais() {
        $retorno = [
            '1' => 'Ativo',
            '2' => 'Aposentado',
            '3' => 'Pensionista',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da tipo funcional
     * @return string
     */
    public static function getSituacaoFuncional($id = null) {
        switch ($id) {
            case '1' : $retorno = 'Ativo';
                break;
            case '2' : $retorno = 'Aposentado';
                break;
            case '3' : $retorno = 'Pensionista';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os vinculos para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getVinculos() {
        $retorno = [
            '1' => 'Efetivo',
            '2' => 'Comissionado',
            '3' => 'Contratado',
            '4' => 'Aposentado',
            '5' => 'Pensionista',
            '6' => 'Eletivo',
            '7' => 'Estagiário',
        ];

        return $retorno;
    }

    /**
     * Retorna o label do vinculo
     * @return string
     */
    public static function getLabelVctoVinculo($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Vencimentos';
                break;
            case '2' : $retorno = 'Vencimentos';
                break;
            case '3' : $retorno = 'Vencimentos';
                break;
            case '4' : $retorno = 'Proventos';
                break;
            case '5' : $retorno = 'Proventos';
                break;
            case '6' : $retorno = 'Subsídios';
                break;
            case '7' : $retorno = 'Vencimentos';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os vinculos para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getLabelVctoVinculos() {
        $retorno = [
            '1' => 'Vencimentos',
            '2' => 'Vencimentos',
            '3' => 'Vencimentos',
            '4' => 'Proventos',
            '5' => 'Proventos',
            '6' => 'Subsídios',
            '7' => 'Vencimentos',
        ];

        return $retorno;
    }

    /**
     * Retorna o label do vinculo
     * @return string
     */
    public static function getVinculo($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Efetivo';
                break;
            case '2' : $retorno = 'Comissionado';
                break;
            case '3' : $retorno = 'Contratado';
                break;
            case '4' : $retorno = 'Aposentado';
                break;
            case '5' : $retorno = 'Pensionista';
                break;
            case '6' : $retorno = 'Eletivo';
                break;
            case '7' : $retorno = 'Estagiário';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os níveis de escolaridade para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getEscolaridades() {
        $retorno = [
            '1' => '01 - Fundamental Completo',
            '2' => '02 - Fundamental Incompleto',
            '3' => '03 - Médio Completo',
            '4' => '04 - Médio Incompleto',
            '5' => '05 - Superior Completo',
            '6' => '06 - Superior Incompleto',
            '7' => '07 - Outros',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da escolaridade
     * @return string
     */
    public static function getEscolaridade($id = null) {

        switch ($id) {
            case '1' : $retorno = '01 -Fundamental Completo';
                break;
            case '2' : $retorno = '02 -Fundamental Incompleto';
                break;
            case '3' : $retorno = '03 - Médio Completo';
                break;
            case '4' : $retorno = '04 - Médio Incompleto';
                break;
            case '5' : $retorno = '05 - Superior Completo';
                break;
            case '6' : $retorno = '06 - Superior Incompleto';
                break;
            case '7' : $retorno = '07 - Outros';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os níveis de escolaridade para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getEscolaridadesRais() {
        $retorno = [
            '01' => '01 - Analfabeto, inclusive o que, embora tenha recebido instrução, não se alfabetizou',
            '02' => '02 - Até o 5º ano incompleto do Ensino Fundamental (antiga 4º série) ou que se tenha alfabetizado sem ter freqüentado escola regular',
            '03' => '03 - 5º ano completo do Ensino Fundamental',
            '04' => '04 - Do 6º ao 9º ano do Ensino Fundamental incompleto (antiga 5º 8º série)',
            '05' => '05 - Ensino fundamental completo',
            '06' => '06 - Ensino médio incompleto',
            '07' => '07 - Ensino médio completo',
            '08' => '08 - Educação superior incompleta',
            '09' => '09 - Educação superior completa',
            '10' => '10 - Mestrado completo',
            '11' => '11 - Doutorado completo',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da escolaridade perante a RAIS
     * @return string
     */
    public static function getEscolaridadeRais($id = null) {

        switch ($id) {
            case '01' : $retorno = '01 - Analfabeto, inclusive o que, embora tenha recebido instrução, não se alfabetizou';
                break;
            case '02' : $retorno = '02 - Até o 5º ano incompleto do Ensino Fundamental (antiga 4º série) ou que se tenha alfabetizado sem ter freqüentado escola regular';
                break;
            case '03' : $retorno = '03 - 5º ano completo do Ensino Fundamental';
                break;
            case '04' : $retorno = '04 - Do 6º ao 9º ano do Ensino Fundamental incompleto (antiga 5º 8º série)';
                break;
            case '05' : $retorno = '05 - Ensino fundamental completo';
                break;
            case '06' : $retorno = '06 - Ensino médio incompleto';
                break;
            case '07' : $retorno = '07 - Ensino médio completo';
                break;
            case '08' : $retorno = '08 - Educação superior incompleta';
                break;
            case '09' : $retorno = '09 - Educação superior completa';
                break;
            case '10' : $retorno = '10 - Mestrado completo';
                break;
            case '11' : $retorno = '11 - Doutorado completo';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipo de insalubridade para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getMolestias() {
        $retorno = [
            null => 'Nenhuma',
            '1' => '01-AIDS (Síndrome da Imunodeficiência Adquirida)',
            '2' => '02-Alienação mental',
            '3' => '03-Cardiopatia grave',
            '4' => '04-Cegueira',
            '5' => '05-Contaminação por radiação',
            '6' => '06-Doença de Paget em estados avançados (Osteíte deformante)',
            '7' => '07-Doença de Parkinson',
            '8' => '08-Esclerose múltipla',
            '9' => '09-Espondiloartrose anquilosante',
            '10' => '10-Fibrose cística (Mucoviscidose)',
            '11' => '11-Publicidade',
            '12' => '12-Hanseníase',
            '13' => '13-Nefropatia grave',
            '14' => '14-Hepatopatia grave (nos casos de hepatopatia grave somente serão isentos os rendimentos auferidos a partir de 01/01/2005)',
            '15' => '15-Neoplasia maligna',
            '16' => '16-Paralisia irreversível e incapacitante',
            '17' => '17-Tuberculose ativa',
        ];

        return $retorno;
    }

    /**
     * Retorna o label da insalubridade
     * @return string
     */
    public static function getMolestia($id = null) {

        switch ($id) {
            case '1' : $retorno = '01-AIDS (Síndrome da Imunodeficiência Adquirida)';
                break;
            case '2' : $retorno = '02-Alienação mental';
                break;
            case '3' : $retorno = '03-Cardiopatia grave';
                break;
            case '4' : $retorno = '04-Cegueira';
                break;
            case '5' : $retorno = '05-Contaminação por radiação';
                break;
            case '6' : $retorno = '06-Doença de Paget em estados avançados (Osteíte deformante)';
                break;
            case '7' : $retorno = '07-Doença de Parkinson';
                break;
            case '8' : $retorno = '08-Esclerose múltipla';
                break;
            case '9' : $retorno = '09-Espondiloartrose anquilosante';
                break;
            case '10' : $retorno = '10-Fibrose cística (Mucoviscidose)';
                break;
            case '11' : $retorno = '11-Publicidade';
                break;
            case '12' : $retorno = '12-Hanseníase';
                break;
            case '13' : $retorno = '13-Nefropatia grave';
                break;
            case '14' : $retorno = '14-Hepatopatia grave (nos casos de hepatopatia grave somente serão isentos os rendimentos auferidos a partir de 01/01/2005)';
                break;
            case '15' : $retorno = '15-Neoplasia maligna';
                break;
            case '16' : $retorno = '16-Paralisia irreversível e incapacitante';
                break;
            case '17' : $retorno = '17-Tuberculose ativa';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de dependencia servidor X dependente para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getTiposDependencia() {
        $retorno = [
            '1' => 'Cônjuge',
            '2' => 'Filho',
            '3' => 'Pai',
            '4' => 'Mãe',
            '5' => 'Sobrinho',
            '6' => 'Neto',
            '7' => 'Outros',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do tipo de dependência
     * @return string
     */
    public static function getTipoDependencia($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Cônjuge';
                break;
            case '2' : $retorno = 'Filho';
                break;
            case '3' : $retorno = 'Pai';
                break;
            case '4' : $retorno = 'Mãe';
                break;
            case '5' : $retorno = 'Sobrinho';
                break;
            case '6' : $retorno = 'Neto';
                break;
            case '7' : $retorno = 'Outros';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de dependencia servidor X dependente para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getManadTipoFolhas() {
        $retorno = [
            '1' => 'Folha normal',
            '2' => 'Folha de 13º salário',
            '3' => 'Folha de férias',
            '4' => 'Folha complementar à normal',
            '5' => 'Folha complementar ao 13º',
            '6' => 'Outras folhas',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do tipo de dependência
     * @return string
     */
    public static function getManadTipoFolha($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Folha normal';
                break;
            case '2' : $retorno = 'Folha de 13º salário';
                break;
            case '3' : $retorno = 'Folha de férias';
                break;
            case '4' : $retorno = 'Folha complementar à normal';
                break;
            case '5' : $retorno = 'Folha complementar ao 13º';
                break;
            case '6' : $retorno = 'Outras folhas';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos FPAS para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCodigosFpas() {
        $retorno = [
            '582' => '582 - Órgão público',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do código FPAS
     * @return string
     */
    public static function getCodigoFpas($id = null) {

        switch ($id) {
            case '582' : $retorno = '582 - Órgão público';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos GPS para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCodigosGps() {
        $retorno = [
            '2402' => '2402 - Órgão público',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do código GPS
     * @return string
     */
    public static function getCodigoGps($id = null) {

        switch ($id) {
            case '2402' : $retorno = '2402 - Órgão público';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos CNAE para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCodigosCnae() {
        $retorno = [
            '8411600' => '8411600 - Administração pública'
        ];
        return $retorno;
    }

    /**
     * Retorna o label do código CNAE
     * @return string
     */
    public static function getCodigoCnae($id = null) {

        switch ($id) {
            case '8411600' : $retorno = '8411600 - Administração pública';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos FGTS para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getCodigosFgts() {
        $retorno = [
            '115' => '115 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL.',
            '130' => '130 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVAS AO TRABALHADOR AVULSO PORTURARIO.',
            '135' => '135 - RECOLHIMENTO E/OU DECLARACAO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVAS AO TRABALHADOR AVULSO NAO PORTUARIO;',
            '145' => '145 - RECOLHIMENTO AO FGTS DE DIREFERENCAS APURADAS PELA CAIXA.',
            '150' => '150 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL DE EMPRESA PRESTADORA DE SERVICOS COM CESSAO DE MAO-DE-OBRA E EMPRESA DE TRABALHO TEMPORARIO LEI Nº 6.019/74, EM RELACAO AOS EMPREGADOS CEDIDOS, OU DE OBRA DE CONSTRUCAO CIVIL - EMPREITADA PARCIAL.',
            '155' => '155 - RECOLHIMETNO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL DE OBRA DE CONSTRUCAO CIVIL - EMPREITADA PARCIAL.',
            '211' => '211 - DECLARACAO PARA A PREVIDENCIA SOCIAL DE COOPERATIVA DE TRABALHO RELATIVA AOS CONTRIBUINTES INDIVIDUAIS COOPERADOS QUE PRESTAM SERVICOS E TOMADORES.',
            '307' => '307 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS.',
            '317' => '317 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS EDE EMPRESA COM TOMADOR DE SERVICOS.',
            '327' => '327 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.',
            '337' => '337 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM O FGTS DE EMPRESA COM TOMADOR DE SERVICOS, PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.',
            '345' => '345 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM O FGTS RELATIVO A DIFERENCA DE RECOLHIMENTO, PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.',
            '418' => '418 - RECOLHIMENTO RECURSAL PARA O FGTS.',
            '604' => '604 - RECOLHIMENTO AO FGTS DE ENTIDADES COM FINS FILANTROPICOS - DECRETO-LEI Nº 194, DE 24/02/1967 (COMPETENCIAS ANTERIORES A 10/1989).',
            '608' => '608 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVO A DIRIGENTE SINDICAL.',
            '640' => '640 - RECOLHIMENTO AO FGTS PARA EMPREGADO NAO OPTANTE (COMPETENCIA ANTERIOR A 10/1988).',
            '650' => '650 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVO A ANISTIADOS, RECLAMATORIA TRABALHAISTAS COM RECONHECIMENTO DE VINCULO, ACORDO OU DISSIDIO OU CONVENCAO COLETIVA, COMISSAO CONCICILIACAO PREVIA OU NUCLEO INTERSINDICAL CONCILIACAO TRABALHISTA.',
            '660' => '660 - RECOLHIMENTO EXCLUSIVO AO FGTS RELATIVO A ANISTIADOS, CONVERSAO LICENCA SAUDE EM ACIDENTE TRABALHO, RECLAMATORIA TRABALHISTA, ACORODO OU DISSIDIO OU CONVENCAO COLETIVA, COMISSAO CONCILIACAO PREVIA OU NUCLEO INTERSINDICAL CONCILIACAO TRABALHISTA.',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do código FGTS
     * @return string
     */
    public static function getCodigoFgts($id = null) {

        switch ($id) {

            case '115' : $retorno = '115 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL.';
                break;
            case '130' : $retorno = '130 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVAS AO TRABALHADOR AVULSO PORTURARIO.';
                break;
            case '135' : $retorno = '135 - RECOLHIMENTO E/OU DECLARACAO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVAS AO TRABALHADOR AVULSO NAO PORTUARIO';
                break;
            case '145' : $retorno = '145 - RECOLHIMENTO AO FGTS DE DIREFERENCAS APURADAS PELA CAIXA.';
                break;
            case '150' : $retorno = '150 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL DE EMPRESA PRESTADORA DE SERVICOS COM CESSAO DE MAO-DE-OBRA E EMPRESA DE TRABALHO TEMPORARIO LEI Nº 6.019/74, EM RELACAO AOS EMPREGADOS CEDIDOS, OU DE OBRA DE CONSTRUCAO CIVIL - EMPREITADA PARCIAL.';
                break;
            case '155' : $retorno = '155 - RECOLHIMETNO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL DE OBRA DE CONSTRUCAO CIVIL - EMPREITADA PARCIAL.';
                break;
            case '211' : $retorno = '211 - DECLARACAO PARA A PREVIDENCIA SOCIAL DE COOPERATIVA DE TRABALHO RELATIVA AOS CONTRIBUINTES INDIVIDUAIS COOPERADOS QUE PRESTAM SERVICOS E TOMADORES.';
                break;
            case '307' : $retorno = '307 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS.';
                break;
            case '317' : $retorno = '317 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS EDE EMPRESA COM TOMADOR DE SERVICOS.';
                break;
            case '327' : $retorno = '327 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM FGTS PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.';
                break;
            case '337' : $retorno = '337 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM O FGTS DE EMPRESA COM TOMADOR DE SERVICOS, PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.';
                break;
            case '345' : $retorno = '345 - RECOLHIMENTO DE PARCELAMENTO DE DEBITO COM O FGTS RELATIVO A DIFERENCA DE RECOLHIMENTO, PRIORIZANDO OS VALORES DEVIDOS AOS TRABALHADORES.';
                break;
            case '418' : $retorno = '418 - RECOLHIMENTO RECURSAL PARA O FGTS.';
                break;
            case '604' : $retorno = '604 - RECOLHIMENTO AO FGTS DE ENTIDADES COM FINS FILANTROPICOS - DECRETO-LEI Nº 194, DE 24/02/1967 (COMPETENCIAS ANTERIORES A 10/1989).';
                break;
            case '608' : $retorno = '608 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVO A DIRIGENTE SINDICAL.';
                break;
            case '640' : $retorno = '640 - RECOLHIMENTO AO FGTS PARA EMPREGADO NAO OPTANTE (COMPETENCIA ANTERIOR A 10/1988).';
                break;
            case '650' : $retorno = '650 - RECOLHIMENTO AO FGTS E INFORMACOES A PREVIDENCIA SOCIAL RELATIVO A ANISTIADOS, RECLAMATORIA TRABALHAISTAS COM RECONHECIMENTO DE VINCULO, ACORDO OU DISSIDIO OU CONVENCAO COLETIVA, COMISSAO CONCICILIACAO PREVIA OU NUCLEO INTERSINDICAL CONCILIACAO TRABALHISTA.';
                break;
            case '660' : $retorno = '660 - RECOLHIMENTO EXCLUSIVO AO FGTS RELATIVO A ANISTIADOS, CONVERSAO LICENCA SAUDE EM ACIDENTE TRABALHO, RECLAMATORIA TRABALHISTA, ACORODO OU DISSIDIO OU CONVENCAO COLETIVA, COMISSAO CONCILIACAO PREVIA OU NUCLEO INTERSINDICAL CONCILIACAO TRABALHISTA.';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os sexos para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getSexos() {
        $retorno = [
            '0' => 'Masculino',
            '1' => 'Feminino',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do sexo
     * @return string
     */
    public static function getSexo($id = null) {

        switch ($id) {
            case '0' : $retorno = 'Masculino';
                break;
            case '1' : $retorno = 'Feminino';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as raças para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getRacas() {
        $retorno = [
            '1' => 'Branca',
            '2' => 'Preta',
            '3' => 'Amarela',
            '4' => 'Parda',
            '5' => 'Indígena',
            '6' => 'Amarela',
        ];
        return $retorno;
    }

    /**
     * Retorna o label da raça
     * @return string
     */
    public static function getRaca($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Branca';
                break;
            case '2' : $retorno = 'Preta';
                break;
            case '3' : $retorno = 'Amarela';
                break;
            case '4' : $retorno = 'Parda';
                break;
            case '5' : $retorno = 'Indígena';
                break;
            case '6' : $retorno = 'Amarela';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os estados civís para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getEstados_civis() {
        $retorno = [
            '1' => 'Casado',
            '2' => 'Solteiro',
            '3' => 'Viúvo',
            '4' => 'Separado Judicialmente',
            '5' => 'Divorciado',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do estado cicil
     * @return string
     */
    public static function getEstado_civil($id = null) {

        switch ($id) {
            case '1' : $retorno = 'Casado';
                break;
            case '2' : $retorno = 'Solteiro';
                break;
            case '3' : $retorno = 'Viúvo';
                break;
            case '4' : $retorno = 'Separado Judicialmente';
                break;
            case '5' : $retorno = 'Divorciado';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os estados civís para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getTiposdeficiencia() {
        $retorno = [
            '0' => 'Sem deficiência',
            '1' => 'Física',
            '2' => 'Auditiva',
            '3' => 'Visual',
            '4' => 'Intelectual(Mental)',
            '5' => 'Múltipla',
            '6' => 'Reabilitado',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do estado cicil
     * @return string
     */
    public static function getTipodeficiencia($id = null) {

        switch ($id) {
            case '0' : $retorno = 'Sem deficiência';
                break;
            case '1' : $retorno = 'Física';
                break;
            case '2' : $retorno = 'Auditiva';
                break;
            case '3' : $retorno = 'Visual';
                break;
            case '4' : $retorno = 'Intelectual(Mental)';
                break;
            case '5' : $retorno = 'Múltipla';
                break;
            case '6' : $retorno = 'Reabilitado';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os sexos para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getNacionalidades() {
        $retorno = [
            '10' => 'Brasileiro',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do sexo
     * @return string
     */
    public static function getNacionalidade($id = null) {

        switch ($id) {
            case '10' : $retorno = 'Brasileiro';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os meses para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getMeses() {
        $retorno = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro',
        ];
        return $retorno;
    }

    /**
     * Retorna o label do Mês
     * @return string
     */
    public static function getMes($id = null) {

        switch ($id) {
            case '01' : $retorno = 'Janeiro';
                break;
            case '02' : $retorno = 'Fevereiro';
                break;
            case '03' : $retorno = 'Março';
                break;
            case '04' : $retorno = 'Abril';
                break;
            case '05' : $retorno = 'Maio';
                break;
            case '06' : $retorno = 'Junho';
                break;
            case '07' : $retorno = 'Julho';
                break;
            case '08' : $retorno = 'Agosto';
                break;
            case '09' : $retorno = 'Setembro';
                break;
            case '10' : $retorno = 'Outubro';
                break;
            case '11' : $retorno = 'Novembro';
                break;
            case '12' : $retorno = 'Dezembro';
                break;
            case '13' : $retorno = '13º';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os meses para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getMesesAbrev($decimo = false) {
        $retorno = [
            '01' => 'Jan',
            '02' => 'Fev',
            '03' => 'Mar',
            '04' => 'Abr',
            '05' => 'Mai',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Ago',
            '09' => 'Set',
            '10' => 'Out',
            '11' => 'Nov',
            '12' => 'Dez',
        ];
        if ($decimo) {
            $retorno['13'] = '13º';
        }
        return $retorno;
    }

    /**
     * Retorna o label do Mês
     * @return string
     */
    public static function getMesAbrev($id = null) {

        switch ($id) {
            case '01' : $retorno = 'Jan';
                break;
            case '02' : $retorno = 'Fev';
                break;
            case '03' : $retorno = 'Mar';
                break;
            case '04' : $retorno = 'Abr';
                break;
            case '05' : $retorno = 'Mai';
                break;
            case '06' : $retorno = 'Jun';
                break;
            case '07' : $retorno = 'Jul';
                break;
            case '08' : $retorno = 'Ago';
                break;
            case '09' : $retorno = 'Set';
                break;
            case '10' : $retorno = 'Out';
                break;
            case '11' : $retorno = 'Nov';
                break;
            case '12' : $retorno = 'Dez';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as UF para alimentar uma dropdowlist p.ex.
     * @return string
     */
    public static function getUfs() {
        $retorno = [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
        return $retorno;
    }

    /**
     * Retorna o label da UF
     * @return string
     */
    public static function getUf($id = null) {

        switch ($id) {
            case 'AC' : $retorno = 'Acre';
                break;
            case 'AL' : $retorno = 'Alagoas';
                break;
            case 'AP' : $retorno = 'Amapá';
                break;
            case 'AM' : $retorno = 'Amazonas';
                break;
            case 'BA' : $retorno = 'Bahia';
                break;
            case 'CE' : $retorno = 'Ceará';
                break;
            case 'DF' : $retorno = 'Distrito Federal';
                break;
            case 'ES' : $retorno = 'Espírito Santo';
                break;
            case 'GO' : $retorno = 'Goiás';
                break;
            case 'MA' : $retorno = 'Maranhão';
                break;
            case 'MT' : $retorno = 'Mato Grosso';
                break;
            case 'MS' : $retorno = 'Mato Grosso do Sul';
                break;
            case 'MG' : $retorno = 'Minas Gerais';
                break;
            case 'PA' : $retorno = 'Pará';
                break;
            case 'PB' : $retorno = 'Paraíba';
                break;
            case 'PR' : $retorno = 'Paraná';
                break;
            case 'PE' : $retorno = 'Pernambuco';
                break;
            case 'PI' : $retorno = 'Piauí';
                break;
            case 'RJ' : $retorno = 'Rio de Janeiro';
                break;
            case 'RN' : $retorno = 'Rio Grande do Norte';
                break;
            case 'RS' : $retorno = 'Rio Grande do Sul';
                break;
            case 'RO' : $retorno = 'Rondônia';
                break;
            case 'RR' : $retorno = 'Roraima';
                break;
            case 'SC' : $retorno = 'Santa Catarina';
                break;
            case 'SP' : $retorno = 'São Paulo';
                break;
            case 'SE' : $retorno = 'Sergipe';
                break;
            case 'TO' : $retorno = 'Tocantins';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna a classe do evento forma mais comum ao usuário
     * @param type $classeEvento
     * @return string
     */
    public static function getEventoDescri($classeEvento) {
        switch ($classeEvento) {
            case 'actionIndex' : $classeEvento = 'Indexado';
                break;
            case 'actionView' : $classeEvento = 'Visualização';
                break;
            case 'actionC' : $classeEvento = 'Registro criado';
                break;
            case 'actionU' : $classeEvento = 'Registro editado';
                break;
            case 'actionDlt' : $classeEvento = 'Registro cancelado';
                break;
            case 'actionDpl' : $classeEvento = 'Registro duplicado';
                break;
            case 'actionLogin' : $classeEvento = 'Usuário entrou';
                break;
            case 'actionLogout' : $classeEvento = 'Usuário saiu';
                break;
            case 'Novo Sistema' : $classeEvento = 'Novo Sistema';
                break;
            case 'actionSetstatus' : $classeEvento = 'Alteração de status';
                break;
            case 'actionPhoto' : $classeEvento = 'Envio de foto';
                break;
            default : $classeEvento = $classeEvento;
        }
        return $classeEvento;
    }

    /**
     * Retorna as classes de evento para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getEventoDescris() {
        $return = [
            'actionIndex' => 'Indexado',
            'actionView' => 'Visualização',
            'actionC' => 'Registro criado',
            'actionU' => 'Registro editado',
            'actionDlt' => 'Registro cancelado',
            'actionDpl' => 'Registro duplicado',
            'actionLogin' => 'Usuário entrou',
            'actionLogout' => 'Usuário saiu',
            'Novo Sistema' => 'Novo Sistema',
            'actionSetstatus' => 'Alteração de status',
            'actionPhoto' => 'Envio de foto',
        ];
        return $return;
    }

    /**
     * Retorna as TimeZones Brasileiras
     * @param type $tz
     * @return string
     */
    public static function getTZ($tz) {
        switch ($tz) {
            case 'AC' : $tz = 'America/Rio_branco';
                break;
            case 'AL' : $tz = 'America/Maceio';
                break;
            case 'AP' : $tz = 'America/Belem';
                break;
            case 'AM' : $tz = 'America/Manaus';
                break;
            case 'BA' : $tz = 'America/Bahia';
                break;
            case 'CE' : $tz = 'America/Fortaleza';
                break;
            case 'DF' : $tz = 'America/Sao_Paulo';
                break;
            case 'ES' : $tz = 'America/Sao_Paulo';
                break;
            case 'GO' : $tz = 'America/Sao_Paulo';
                break;
            case 'MA' : $tz = 'America/Fortaleza';
                break;
            case 'MT' : $tz = 'America/Cuiaba';
                break;
            case 'MS' : $tz = 'America/Campo_Grande';
                break;
            case 'MG' : $tz = 'America/Sao_Paulo';
                break;
            case 'PR' : $tz = 'America/Sao_Paulo';
                break;
            case 'PB' : $tz = 'America/Fortaleza';
                break;
            case 'PA' : $tz = 'America/Belem';
                break;
            case 'PE' : $tz = 'America/Recife';
                break;
            case 'PI' : $tz = 'America/Fortaleza';
                break;
            case 'RJ' : $tz = 'America/Sao_Paulo';
                break;
            case 'RN' : $tz = 'America/Fortaleza';
                break;
            case 'RS' : $tz = 'America/Sao_Paulo';
                break;
            case 'RO' : $tz = 'America/Porto_Velho';
                break;
            case 'RR' : $tz = 'America/Boa_Vista';
                break;
            case 'SC' : $tz = 'America/Sao_Paulo';
                break;
            case 'SE' : $tz = 'America/Maceio';
                break;
            case 'SP' : $tz = 'America/Sao_Paulo';
                break;
            case 'TO' : $tz = 'America/Araguaia';
                break;
            default : $tz = 'America/Recife';
        }
        return $tz;
    }

    /**
     * Retorna as classes de evento para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getTZs() {
        $return = [
            'AC' => 'America/Rio_branco',
            'AL' => 'America/Maceio',
            'AP' => 'America/Belem',
            'AM' => 'America/Manaus',
            'BA' => 'America/Bahia',
            'CE' => 'America/Fortaleza',
            'DF' => 'America/Sao_Paulo',
            'ES' => 'America/Sao_Paulo',
            'GO' => 'America/Sao_Paulo',
            'MA' => 'America/Fortaleza',
            'MT' => 'America/Cuiaba',
            'MS' => 'America/Campo_Grande',
            'MG' => 'America/Sao_Paulo',
            'PR' => 'America/Sao_Paulo',
            'PB' => 'America/Fortaleza',
            'PA' => 'America/Belem',
            'PE' => 'America/Recife',
            'PI' => 'America/Fortaleza',
            'RJ' => 'America/Sao_Paulo',
            'RN' => 'America/Fortaleza',
            'RS' => 'America/Sao_Paulo',
            'RO' => 'America/Porto_Velho',
            'RR' => 'America/Boa_Vista',
            'SC' => 'America/Sao_Paulo',
            'SE' => 'America/Maceio',
            'SP' => 'America/Sao_Paulo',
            'TO' => 'America/Araguaia',
            'AL' => 'America/Maceio',
        ];
        return $return;
    }

    /**
     * Retorna o tipo de previdência
     * @param type $v
     * @return string
     */
    public static function getTipoPrevidencia($v) {
        switch ($v) {
            case 1 : $retorno = 'RPPS';
                break;
            case 2 : $retorno = 'INSS';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de previdencia para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getTiposPrevidencia() {
        $return = [
            1 => 'RPPS',
            2 => 'INSS',
        ];
        return $return;
    }

    /**
     * Retorna o tipo de beneficio
     * @param type $v
     * @return string
     */
    public static function getTipoBeneficio($v) {
        switch ($v) {
            case 1 : $retorno = 'Invalidez';
                break;
            case 2 : $retorno = 'Idade';
                break;
            case 3 : $retorno = 'Tempo de contribuição';
                break;
            case 4 : $retorno = 'Morte';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos de beneficio para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getTiposBeneficio() {
        $return = [
            1 => 'Invalidez',
            2 => 'Idade',
            3 => 'Tempo de contribuição',
            4 => 'Morte',
        ];
        return $return;
    }

    /**
     * Retorna o código de afastamento
     * @param type $v
     * @return string
     */
    /*
     * ATENÇÃO::: CASO ALTERE ESTA FUNÇÃO, LEMBRAR DE ALTERAR A FUNÇÃO NO BD COM O MESMO NOME
     */
    public static function getCodAfastamento($v) {
        switch ($v) {
            case 'Q1' : $retorno = 'Q1 - AFASTAMENTO TEMPORÁRIO POR MOTIVO DE LICENÇA-MATERNIDADE (120 DIAS)';
                break;
            case 'X' : $retorno = 'X - LICENÇA SEM VENCIMENTOS';
                break;
            case 'P1' : $retorno = 'P1 - AFASTAMENTO TEMPORÁRIO POR MOTIVO DE DOENÇA, POR PERÍODO SUPERIOR A 15 DIAS';
                break;
            case 'H' : $retorno = 'H  - RESCISÃO, COM JUSTA CAUSA, POR INICIATIVA DO EMPREGADOR';
                break;
            case 'I1' : $retorno = 'I1 - RESCISÃO SEM JUSTA CAUSA, POR INICIATIVA DO EMPREGADOR, INCLUSIVE RESCISÃO ANTECIPADA DO CONTRATO A TERMO';
                break;
            case 'I2' : $retorno = 'I2 - RESCISÃO POR CULPA RECÍPROCA OU FORÇA MAIOR';
                break;
            case 'I3' : $retorno = 'I3 - RESCISÃO POR TÉRMINO DO CONTRATO A TERMO';
                break;
            case 'I4' : $retorno = 'I4 - RESCISÃO SEM JUSTA CAUSA DO CONTRATO DE TRABALHO DO EMPREGADO DOMÉSTICO, POR INICIATIVA DO EMPREGADOR';
                break;
            case 'J' : $retorno = 'J  - RESCISÃO DO CONTRATO DE TRABALHO POR INICIATIVA DO EMPREGADO';
                break;
            case 'K' : $retorno = 'K  - RESCISÃO A PEDIDO DO EMPREGADO OU POR INICIATIVA DO EMPREGADOR, COM JUSTA CAUSA, NO CASO DE EMPREGADO NÃO OPTANTE, COM MENOS DE UM ANO DE SERVIÇO';
                break;
            case 'L' : $retorno = 'L  - OUTROS MOTIVOS DE RESCISÃO DO CONTRATO DE TRABALHO';
                break;
            case 'S2' : $retorno = 'S2 - FALECIMENTO';
                break;
            case 'S3' : $retorno = 'S3 - FALECIMENTO MOTIVADO POR ACIDENTE DE TRABALHO';
                break;
            case 'U1' : $retorno = 'U1 - APOSENTADORIA';
                break;
            case 'U3' : $retorno = 'U3 - APOSENTADORIA POR INVALIDEZ';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos de afastamento para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getCodsAfastamento() {
        $return = [
            'Afastamento' => [
                'Q1' => 'Q1 - AFASTAMENTO TEMPORÁRIO POR MOTIVO DE LICENÇA-MATERNIDADE (120 DIAS)',
                'X' => 'X - LICENÇA SEM VENCIMENTOS',
                'P1' => 'P1 - AFASTAMENTO TEMPORÁRIO POR MOTIVO DE DOENÇA, POR PERÍODO SUPERIOR A 15 DIAS',
            ],
            'Desligamento' => [
                'H' => 'H - RESCISÃO, COM JUSTA CAUSA, POR INICIATIVA DO EMPREGADOR',
                'I1' => 'I1 - RESCISÃO SEM JUSTA CAUSA, POR INICIATIVA DO EMPREGADOR, INCLUSIVE RESCISÃO ANTECIPADA DO CONTRATO A TERMO',
                'I2' => 'I2 - RESCISÃO POR CULPA RECÍPROCA OU FORÇA MAIOR',
                'I3' => 'I3 - RESCISÃO POR TÉRMINO DO CONTRATO A TERMO',
                'I4' => 'I4 - RESCISÃO SEM JUSTA CAUSA DO CONTRATO DE TRABALHO DO EMPREGADO DOMÉSTICO, POR INICIATIVA DO EMPREGADOR',
                'J' => 'J - RESCISÃO DO CONTRATO DE TRABALHO POR INICIATIVA DO EMPREGADO',
                'K' => 'K - RESCISÃO A PEDIDO DO EMPREGADO OU POR INICIATIVA DO EMPREGADOR, COM JUSTA CAUSA, NO CASO DE EMPREGADO NÃO OPTANTE, COM MENOS DE UM ANO DE SERVIÇO',
                'L' => 'L - OUTROS MOTIVOS DE RESCISÃO DO CONTRATO DE TRABALHO',
                'S2' => 'S2 - FALECIMENTO',
                'S3' => 'S3 - FALECIMENTO MOTIVADO POR ACIDENTE DE TRABALHO',
                'U1' => 'U1 - APOSENTADORIA',
                'U3' => 'U3 - APOSENTADORIA POR INVALIDEZ',
            ]
        ];
        return $return;
    }

    /**
     * Retorna o código de retorno
     * @param type $v
     * @return string
     */
    public static function getCodRetorno($v) {
        switch ($v) {
            case 'Q1' : $retorno = 'Z1 - RETORNO DE AFASTAMENTO TEMPORÁRIO POR MOTIVO DE LICENÇA-MATERNIDADE';
                break;
            case 'X' : $retorno = 'Z5 - OUTROS RETORNOS DE AFASTAMENTO TEMPORÁRIO E/OU LICENÇA';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos de retorno para preencher um dropdownlist
     * @param type $id
     * @return string
     */
//    public static function getCodsRetorno($id = null) {
//        if ($id == 'Q1') {
//            $return = [
//                'Z1' => 'Z1 - RETORNO DE AFASTAMENTO TEMPORÁRIO POR MOTIVO DE LICENÇA-MATERNIDADE',
//            ];
//        } else {
//            $return = [
//                'Z5' => 'Z5 - OUTROS RETORNOS DE AFASTAMENTO TEMPORÁRIO E/OU LICENÇA',
//            ];
//        }
////        return Json::encode($return);
//        return $return;
//    }

    /**
     * Retorna os códigos de retorno para preencher um dropdownlist
     * @param type $id
     * @return string
     */
    public static function getCodsRetorno($id = null) {
        if ($id == 'Q1') {
            $return = ['id' => 'Z1', 'name' => 'Z1 - RETORNO DE AFASTAMENTO TEMPORÁRIO POR MOTIVO DE LICENÇA-MATERNIDADE'];
        } else {
            $return = ['id' => 'Z5', 'name' => 'Z5 - OUTROS RETORNOS DE AFASTAMENTO TEMPORÁRIO E/OU LICENÇA'];
        }
        return $return;
    }

    /**
     * Retorna o código de retorno
     * @param type $v
     * @return string
     */
    public static function getCodDesligamento($v) {
        switch ($v) {
            case '10' : $retorno = '10 - RESCISÃO DE CONTRATO DE TRABALHO POR JUSTA CAUSA E INICIATIVA DO EMPREGADOR OU DEMISSÃO DE SERVIDOR';
                break;
            case '11' : $retorno = '11 - RESCISÃO DE CONTRATO DE TRABALHO SEM JUSTA CAUSA POR INICIATIVA DO EMPREGADOR OU EXONERAÇÃO DE OFICIO DE SERVIDOR DE CARGO EFETIVO OU EXONERAÇÃO DE CARGO EM COMISSÃO';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os códigos de retorno para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getCodsDesligamento() {
        $return = [
            '10' => '10 - RESCISÃO DE CONTRATO DE TRABALHO POR JUSTA CAUSA E INICIATIVA DO EMPREGADOR OU DEMISSÃO DE SERVIDOR',
            '11' => '11 - RESCISÃO DE CONTRATO DE TRABALHO SEM JUSTA CAUSA POR INICIATIVA DO EMPREGADOR OU EXONERAÇÃO DE OFICIO DE SERVIDOR DE CARGO EFETIVO OU EXONERAÇÃO DE CARGO EM COMISSÃO',
        ];
        return $return;
    }

    /**
     * Retorna o tipo do evento/rúbrica
     * @param type $v
     * @return string
     */
    public static function getCD($v) {
        switch (strtolower($v)) {
            case '0' : $retorno = 'Crédito';
                break;
            case '1' : $retorno = 'Débito';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna os tipos do evento/rúbrica para preencher um dropdownlist
     * @param type $classeEvento
     * @return string
     */
    public static function getCDs() {
        $return = [
            '0' => 'Crédito',
            '1' => 'Débito',
        ];
        return $return;
    }

    /**
     * Retorna a situação do parâmetro
     * @param type $v
     * @return string
     */
    public static function getSitParametro($v) {
        switch (strtolower($v)) {
            case '0' : $retorno = 'Fechada';
                break;
            case '1' : $retorno = 'Aberta';
                break;
            default : $retorno = 'Não definido';
        }
        return $retorno;
    }

    /**
     * Retorna as situações dos parâmetros de folha para preencher um dropdownlist
     * @param type $return
     * @return string
     */
    public static function getSitsParametro() {
        $return = [
            '0' => 'Fechada',
            '1' => 'Aberta',
        ];
        return $return;
    }

}
