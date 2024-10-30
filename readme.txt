=== imgResultsFromReferer ===
Contributors: m.greiling
Tags: admin, backend, referer, image, statistic, google image search, search results, exploit get params
Requires at least: 3.0.1
Tested up to: 4.7
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=N44CVCHAWSL7E
License: CC-NC-BY-SA 4.0
License URI: http://creativecommons.org/licenses/by-nc-sa/4.0/

Displays google image search results in admin backend. Exploits information from HTTP_REFERER data of many wordpress statistic tools.

== Description ==

EN:

Displays google image search results in admin backend. Exploits information from HTTP_REFERER data of many wordpress statistic tools.
The requirement for this to work is that the statistic tool captures the HTTP_REFERER with a timestamp or date field in the same record.

Settings:

Thumbnail size depends on user theme, usually 150x150 pixels - this can be changed
Set how many thumbnails are displayed (realtime)
Set how many thumbnails are displayed by month (rank 1 - n)
Set in which table information is stored (without leading prefix)
Set name of column containing HTTP_REFERER (works best with TEXT or VARCHAR > 500)
Set name of column containing DATE or DATETIME or TIMESTAMP
The URL of your blog will be removed through processing. Additional strings like outdated or old URL can also be removed, when entered.


DE:

Zeigt die Bilder im Backend an, die in der Google-Bildersuche aufgerufen wurden und auf den Blog führten. Die Darstellung im Backend erfolgt in der Thumbnail-Größe des Themes. Suchbegriffe, die mitübertragen wurden, werden ebenfalls angezeigt. 

Einstellungen: 

Thumbnail-Größe, die während dem Hochladen in die Mediathek automatisch generiert wurde (z.B. 150x150).
Anzahl der Bilder, die bei der 'Live'-Ansicht angezeigt werden sollen (Ohne Tabellen-Präfix)
Wie viele Bilder sollen in der monatlichen Übersicht angezeigt werden (Plätze 1 - n)
Name der Spalte, in der der HTTP_REFERER abgelegt wurde (am Besten ist der Datentyp TEXT oder ein VARCHAR > 500)
Name der Spalte, in der ein Datum zu dem Aufruf abgelegt wurde (DATE, DATETIME oder TIMESTAMP)
Während der Verarbeitung wird die URL des Blogs entfernt, sollte der Blog während der statistischen Erfassung die URL einmal gewechselt haben, kann hier ein String eingetragen werden, der ebenfalls entfernt wird



== Installation ==

EN:

1. Upload `imgResultsFromReferer` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy the new menu item "Google Image Results"

DE:

1. Lade den Ordner 'imgResultsFromReferer'  in '/wp-content/plugins/' hoch
2. Aktiviere das Plugin im 'Plugin'-Menü von WordPress
3. Das Plugin taucht als "Ergooglete Bilder" in der Menüspalte auf 

== Frequently Asked Questions ==

there aren't any yet

== Upgrade Notice ==

there aren't any yet

== Known issues ==

EN:

Header Images won't be displayed correctly

DE:

Header-Bilder werden nicht korrekt angezeigt

== Screenshots ==

1. Overview - last clicked images, ordered in rows
2. Total Ranking - all clicked images with counter and avaliable search terms on mouseOver
3. Monthly ranking - as long as your statistic tool collect referring strings
4. Setup panel - easy

== Changelog ==

= 0.1. =
initial release

= 0.2. =
added GROUP BY
several strings comma separated removeable

= 0.3 =
monthly: total / different pictures count 
static domain ready
