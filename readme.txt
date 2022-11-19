
//** Install On localhost
1. Use Jamsrworld2 as License Code
2. Change email setting in admin panel for otp

//** Install On Cpanel
1. You have to manually insert sql file on online hosting
2. After connecting database go to base_url/admin and create admin account
3. Change email setting in admin panel for otp
  

//** How to use your email for otp verification

/*  
    1. Login to your Google account and go to the Security page.
       Scroll down to the Less secure app access section. 
        ---------- OR ------
        Go to https://myaccount.google.com/u/0/lesssecureapps
    2. Turn Off 2 Step Verification if enabled otherwise you can't see 'Turn on Less secure app' option
    3. Turn on Less secure app access. 
*/

 
created by https://jamsrworld.com/

last update 12 April 2022


RewriteEngine on
Options -Indexes


ErrorDocument 404 https://tlpneurship.com/404/

RewriteCond %{DOCUMENT_ROOT}/$1.php -f
RewriteRule ^(.+?)/?$ $1.php [L]

#to drop .php externally from url
RewriteCond %{THE_REQUEST} ^[A-Z]{3,}\s([^.]+)\.php [NC]
RewriteRule ^ %1 [R=302,L]

#to drop .php from url internally
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}\.php -f
RewriteRule ^(.*)$ $1.php


RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(login|register|forgot-password|logout|new-registration|verify-otp|reset-password)$ members/$1 [L]


# php -- BEGIN cPanel-generated handler, do not edit
# Set the “ea-php80” package as the default “PHP” programming language.
<IfModule mime_module>
  AddHandler application/x-httpd-ea-php80 .php .php8 .phtml
</IfModule>
# php -- END cPanel-generated handler, do not edit 
