<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * PDF labels generation
 *
 * User have to select members in the member's list to generate labels.
 * Format is defined in the preferences screen
 *
 * PHP version 5
 *
 * Copyright © 2004-2012 The Galette Team
 *
 * This file is part of Galette (http://galette.tuxfamily.org).
 *
 * Galette is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Galette is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Galette. If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Print
 * @package   Galette
 *
 * @author    Frédéric Jaqcuot <nobody@exemple.com>
 * @author    Johan Cwiklinski <johan@x-tnd.be>
 * @copyright 2004-2012 The Galette Team
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GPL License 3.0 or (at your option) any later version
 * @version   SVN: $Id$
 * @link      http://galette.tuxfamily.org
 */

use Galette\IO\Pdf;

/** @ignore */
require_once 'includes/galette.inc.php';

if ( !$login->isLogged() ) {
    header("location: index.php");
    die();
}
if ( !$login->isAdmin() && !$login->isStaff() ) {
    header("location: voir_adherent.php");
    die();
}

$members = null;
$session = &$_SESSION['galette'][PREFIX_DB . '_' . NAME_DB];
if ( isset($session['filters']['members']) ) {
    $filters =  unserialize($session['filters']['members']);
} else {
    $filters = new MembersList();
}

if ( isset($_GET['from']) && $_GET['from'] === 'mailing' ) {
    //if we're from mailing, we have to retrieve its unreachables members for labels
    $mailing = unserialize($session['mailing']);
    $members = $mailing->unreachables;
} else {
    if ( count($filters->selected) == 0 ) {
        $log->log('No member selected to generate labels', KLogger::INFO);
        header('location:gestion_adherents.php');
        die();
    }

    $members = Galette\Repository\Members::getArrayList($filters->selected);
}

if ( !is_array($members) || count($members) < 1 ) {
    die();
}

$doc_title = _T("Member's Labels");
$doc_subject = _T("Generated by Galette");
$doc_keywords = _T("Labels");
// Create new PDF document
$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8');

// Set document information
$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

// No hearders and footers
$pdf->SetPrintHeader(false);
$pdf->SetPrintFooter(false);
$pdf->setFooterMargin(0);
$pdf->setHeaderMargin(0);

// Show full page
$pdf->SetDisplayMode('fullpage');

// Disable Auto Page breaks
$pdf->SetAutoPageBreak(false, 0);

// Set colors
$pdf->SetDrawColor(160, 160, 160);
$pdf->SetTextColor(0);

// Set margins
$pdf->SetMargins(
    $preferences->pref_etiq_marges_h,
    $preferences->pref_etiq_marges_v
);
// Set font
//$pdf->SetFont("FreeSerif","",PREF_ETIQ_CORPS);

// Set origin
// Top left corner
$yorigin=round($preferences->pref_etiq_marges_v);
$xorigin=round($preferences->pref_etiq_marges_h);
// Label width
$w = round($preferences->pref_etiq_hsize);
// Label heigth
$h = round($preferences->pref_etiq_vsize);
// Line heigth
$line_h=round($h/5);
$nb_etiq=0;

foreach ($members as $member) {
    // Detect page breaks
    if ($nb_etiq % ($preferences->pref_etiq_cols * $preferences->pref_etiq_rows) == 0) {
        $pdf->AddPage();
    }
    // Set font
    $pdf->SetFont(Pdf::FONT, 'B', $preferences->pref_etiq_corps);

    // Compute label position
    $col = $nb_etiq % $preferences->pref_etiq_cols;
    $row = ($nb_etiq / $preferences->pref_etiq_cols) % $preferences->pref_etiq_rows;
    // Set label origin
    $x = $xorigin + $col*(round($preferences->pref_etiq_hsize) + round($preferences->pref_etiq_hspace));
    $y = $yorigin + $row*(round($preferences->pref_etiq_vsize) + round($preferences->pref_etiq_vspace));
    // Draw a frame around the label
    $pdf->Rect($x, $y, $w, $h);
    // Print full name
    $pdf->SetXY($x, $y);
    $pdf->Cell($w, $line_h, $member->sfullname, 0, 0, 'L', 0);
    // Print first line of adress
    $pdf->SetFont(Pdf::FONT, '', $preferences->pref_etiq_corps);
    $pdf->SetXY($x, $y+$line_h);
    $pdf->Cell($w, $line_h, $member->adress, 0, 0, 'L', 0);
    // Print second line of adress
    $pdf->SetXY($x, $y+$line_h*2);
    $pdf->Cell($w, $line_h, $member->adress_continuation, 0, 0, 'L', 0);
    // Print zip code and town
    $pdf->SetFont(Pdf::FONT, 'B', $preferences->pref_etiq_corps);
    $pdf->SetXY($x, $y+$line_h*3);
    $pdf->Cell($w, $line_h, $member->zipcode . ' - ' . $member->town, 0, 0, 'L', 0);
    // Print country
    $pdf->SetFont(Pdf::FONT, 'I', $preferences->pref_etiq_corps);
    $pdf->SetXY($x, $y+$line_h*4);
    $pdf->Cell($w, $line_h, $member->country, 0, 0, 'R', 0);
    $nb_etiq++;
}

// Send PDF code to browser
$pdf->Output(_T("labels_print_filename") . '.pdf', 'D');
?>
