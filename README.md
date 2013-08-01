Pingo
=====
Doug Hatcher superterran@gmail.com

Pingo is a php/ajax script that monitors internet connectivity and will identify current data connection.
Many people can use it at once, just point their system to the host machine and bring Pingo up in a browser.

In 'options' in the browser frontend, you can set a 'repeat' interval for how long between requests. The pingo script running on
the host machine will only attempt the ping once per this interval, The browser front-end will request an
update from pingo every few seconds, and will always return a cached response unless the update interval has
lapsed. This ensures the script isn't piling on any external connectivity issues.

Installation
------------

You need a recent version of PHP with working remote file_get_content function.

1. Clone into a directory
2. Setup vhost and host file to point to directory
4. Ensure directory is writable, this allows for cache files to be made
3. restart apache and view in web browser

You can also setup labels for IP addresses, take a look at the remoteips.json.sample for an example.

