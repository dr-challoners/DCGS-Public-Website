DCGS-Public-Website
===================

.htaccess file for conversion to other formats:
-----------------------------------------------

RewriteEngine On    #Turn on the rewriting engine

#See http://coding.smashingmagazine.com/2011/11/02/introduction-to-url-rewriting/ for support

#General content pages
RewriteRule    ^pages/(.+)/$    content_pages.php?folder=$1   [L]
RewriteRule    ^pages/(.+)/(.*)/(.*)$    content_pages.php?folder=$1&subfolder=$2&page=$3    [L]

#House Competition archives
RewriteRule    ^archive/(.+)/(.*)/(.*)/year(.*)$    content_pages.php?folder=$1&subfolder=$2&page=$3&year=$4    [L]

#Search page
RewriteRule    ^search/    search.php    [L]

#Intranet pages
RewriteRule    ^intranet/?$    intranet.php    [L]
RewriteRule    ^intranet/(.+)$    intranet.php?user=$1    [L]

#News stories
RewriteRule    ^news/(.+)$    content_news.php?story=$1    [L]

#Diary entries, where $1 is the day, $2 the month and $3 the year
RewriteRule    ^diary/([0-9]+)/([0-9]+)/([0-9]+)/?$    diary_display.php?date=$3$2$1    [L]
RewriteRule    ^diary/([0-9]+)/([0-9]+)/([0-9]+)/(.+)$    diary_display.php?date=$3$2$1$4    [L]    #For adding calendar navigation info

#Diary event entries
RewriteRule    ^diary/event/(.+)$    diary_display.php?event=$1    [L]

#Learn websites
RewriteRule    ^learn/([^/.]+)/?$    learn/?subject=$1   [L]
RewriteRule    ^learn/([^/.]+)/([^/.]+)/?$    learn/?subject=$1&folder=$2   [L]
RewriteRule    ^learn/([^/.]+)/([^/.]+)/([^/.]+)/?$    learn/?subject=$1&folder=$2&page=$3   [L]
RewriteRule    ^learn/([^/.]+)/([^/.]+)/([^/.]+)/([^/.]+)/?$    learn/?subject=$1&folder=$2&subfolder=$3&page=$4   [L]
