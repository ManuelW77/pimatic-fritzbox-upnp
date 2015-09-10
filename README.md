# pimatic-fritzbox-upnp

get current used bandwidth from upnp

![example](/example.png?raw=true "Example")


@ pimatic rule manager
![example pimatic rule](/rulemanager.png?raw=true "Example")

or

@ system cron
```
1 * * * * /usr/bin/php /home/pi/scripts/fritzbox/traffic_upnp.php >/dev/null 2>&1
```
