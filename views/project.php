<?php
namespace Webdashboard;

$body_class = $body_class . ' project';
$links = '<script language="javascript" type="text/javascript" src="./assets/js/sorttable.js"></script>';
$content = "
    <table class=\"table\" id=\"project\">
        <caption>L10n Project Dashboard ($project)</caption>
        <thead>
            <tr>
                <th>Locale</th>";

// Display columns name
foreach ($pages as $page) {
    $status_url = LANG_CHECKER . '?locale=all&amp;website=' . $page['site'] . '&amp;file=' . $page['file'];
    $content .= '<td><a href="' . $status_url . '" title="Open the status page for this file">' . $page['file'] . '</a></td>';
}
$content .= '
            </tr>
        </thead>
        <tbody>';

// Display status for all pages per locale
foreach ($status_formatted as $locale => $array_status) {
    $working_on_locamotion = in_array($locale, $locamotion);
    $content .= '<tr>' . "\n"
              . "<td><a href=\"?locale=$locale\">$locale";
    if ($working_on_locamotion) {
        $content .= '<img src="./assets/images/locamotion_16.png" class="locamotion" />';
    }
    $content .= '</a></td>' . "\n";
    foreach ($array_status as $key => $result) {
        $cell = $class = '';

        // This locale does not have this page
        if ($result == 'none') {
            $cell = '1';
            $class = $result;
        } else {
            // Page done
            if ($result  == 'done') {
                $cell = '100%';
                $class = $result;
            // Missing
            } elseif ($result == 'missing') {
                $cell = '0%';
                $class = $result;
            // In progress
            } else {
                $cell = $result;
                $class = 'inprogress';
            }
        }
        $content .= '<td class="' . $class . '">' . $cell . '</td>' . "\n";
    }
    $content .= '</tr>' . "\n";
}
$content .= '</tbody>'
          . '</table>';

// Display stats per page
$content .= '<table class="results">
                <thead>
                  <tr>
                    <th>Page</th><th>Completion</th>
                  </tr>
                </thead>
                <tbody>';

// Sort pages
ksort($locale_done_per_page);
foreach ($locale_done_per_page as $page => $locales) {
    $content .= '<tr><td class="results_file">' . $page . '</td>'
              . '<td class="results_stats"> ' . count($locales) . '/'
              . count($locales_per_page[$page]) . ' perfect locales ('. $page_coverage[$page] . '%)</td></tr>';
}

// Display global stats
$content .= '<tr><th colspan="2" class="final">Total: ' . count($locale_done) . '/' . $total_locales . ' perfect locales (' . $perfect_locales_coverage . '%)</th></tr>'
          . '<tr><th colspan="2">Average: ' . $average_nb_locales . '/' . $total_locales . ' perfect locales (' . $average_coverage . '%)</th></tr>'
          . '</tbody>'
          . '</table>'
          . '<p class="table_legend">Percentages between parenthesis express coverage of our l10n base.</p>';

include __DIR__ . '/../templates/' . $template;
