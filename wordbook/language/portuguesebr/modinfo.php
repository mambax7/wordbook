<?php

/**
 * $Id: main.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook - a multicategory glossary
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

// Informações do módulo
// O nome deste módulo
global $xoopsModule;
define("_MI_WB_MD_NAME", "Dicionário");

// Uma breve descrição deste módulo
define("_MI_WB_MD_DESC", "Um glossário de multicategoria");

// Sub menus no bloco principal
define("_MI_WB_SUB_SMNAME1", "Enviar uma entrada");
define("_MI_WB_SUB_SMNAME2", "Solicitar uma definição");
define("_MI_WB_SUB_SMNAME3", "Buscar uma definição");

define("_MI_WB_RANDOMTERM", "Dicionário - Termo Aleatório");

// Uma breve descrição deste módulo
define("_MI_WB_ALLOWSUBMIT", "1. Os usuários podem enviar entradas?");
define("_MI_WB_ALLOWSUBMITDSC", "Se configurado para 'Sim', os usuários terão acesso ao formulário de envio");

define("_MI_WB_ANONSUBMIT", "2. Os convidados podem enviar entradas?");
define("_MI_WB_ANONSUBMITDSC", "Se configurado para 'Sim', os convidados terão acesso ao formulário de envio");

define("_MI_WB_DATEFORMAT", "3. Em que formato a data deverá aparecer?");
define("_MI_WB_DATEFORMATDSC", "Utilize a parte final do arquivo language/english/global.php para selecionar o estilo a ser mostrado. Examplo: 'd-M-Y H:i' traduzido para '23-Mar-2004 22:35'");

define("_MI_WB_PERPAGE", "4. Numero de entradas por página (Lado administrativo)?");
define("_MI_WB_PERPAGEDSC", "Número de entradas que serão mostradas cada vez que a tabela mostrar as entradas ativadas no lado administrativo.");

define("_MI_WB_PERPAGEINDEX", "5. Número de entradas por página (Lado do usuário)?");
define("_MI_WB_PERPAGEINDEXDSC", "Número de entradas que serão mostradas em cada página no lado administrativo do módulo.");

define("_MI_WB_AUTOAPPROVE", "6. Aprovar as entradas automaticamente?");
define("_MI_WB_AUTOAPPROVEDSC", "Se configurado para 'Sim', serão publicadas as entradas enviadas sem a intervenção do administrador.");

define("_MI_WB_MULTICATS", "7. Você deseja ter categorias de glossários?");
define("_MI_WB_MULTICATSDSC", "Se configurado para 'Sim', será permitido você ter categorias de glossarios. Se configurado para não, você terá uma única categoria automatica.");

define("_MI_WB_CATSINMENU","8. As categorias deve ser mostradas no menu?");
define("_MI_WB_CATSINMENUDSC","Se configurado para 'Sim', se você deseja links para as categorias no menu principal.");

define("_MI_WB_CATSPERINDEX","9. Número de categorias por página (Lado administrativo)?");
define("_MI_WB_CATSPERINDEXDSC","Isto definirá quantas categorias serão mostradas na página index.");

define("_MI_WB_ALLOWADMINHITS", "10. Os acessos do administrador serão incluidos na contagem?");
define("_MI_WB_ALLOWADMINHITSDSC", "Se configurado para 'Sim', o contador será acrescido a cada entrada do administrador.");

define("_MI_WB_MAILTOADMIN", "11. Enviar email para o administrador a cada novo envio?");
define("_MI_WB_MAILTOADMINDSC", "Se configurado para 'Sim', o administrador receberá um email para toda a entrada enviada.");
define("_MI_WB_RANDOMLENGTH", "12. Cumprimentos da string a mostrar nas definições randômica?");
define("_MI_WB_RANDOMLENGTHDSC", "Quantos caracteres você deseja mostrar nas caixas de termo randômico, tanto na página principal como no bloco? (Default: 150)");

define("_MI_WB_LINKTERMS", "13. Mostar links para outro glossário de termos nas definições?");
define("_MI_WB_LINKTERMSDSC", "Se configurado para 'Sim', automaticamente suas definições serão linkadas com aquelas que você já tem em seus glossários.");

// Nomes dos itens do menu de administração
define("_MI_WB_ADMENU1", "Index");
define("_MI_WB_ADMENU2", "Categorias");
define("_MI_WB_ADMENU3", "Entradas");
define("_MI_WB_ADMENU4", "Blocos");
define("_MI_WB_ADMENU5", "Ir para o módulo");
//mondarse
define("_MI_WB_ADMENU6", "Importar");

//Nomes dos blocos e bloco de informação
define("_MI_WB_ENTRIESNEW", "Dicionário - Termos Recentes");
define("_MI_WB_ENTRIESTOP", "Dicionário - Termos mais Lidos");

// added in version 1.17
define("_MI_WB_ADMENU8", "Enviados");
define("_MI_WB_ADMENU10", "Sobre");


?>
