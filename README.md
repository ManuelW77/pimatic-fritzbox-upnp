# pimatic-fritzbox-upnp

get current used bandwidth from upnp (tested with my 7390 / OS 06.30 and 7490 with 6.51 & 6.60)

![example](/example.png?raw=true "Example")


@ pimatic rule manager
![example pimatic rule](/rulemanager.png?raw=true "Example")

or

@ system cron
```
1 * * * * /usr/bin/php /home/pi/scripts/fritzbox/traffic_upnp.php >/dev/null 2>&1
```


requirements
- pimatic (https://github.com/pimatic/pimatic)
- pimatic-shell-execute (https://github.com/pimatic/pimatic-shell-execute)
- one variable @ pimatic
- variableDevice @ pimatic
- php-cli with curl (apt-get install php5-cli php5-curl)
- fritzbox router with enabled upnp feature

device to show variable @ gui
````
    {
      "id": "fritzbox-bw-current",
      "name": "Bandbreite",
      "class": "VariablesDevice",
      "variables": [
        {
          "name": "fritzbox-bw-current",
          "expression": "($fritzbox-bw-current)",
          "type": "string",
          "unit": "GB",
          "discrete": true,
          "label": "aktuelle Bandbreite"
        }
      ]
    }
````

to disable database logging
````
       {
          "deviceId": "fritzbox-bw-current",
          "attributeName": "*",
          "expire": "0d",
          "type": "*"
        }
````

variable @ config
````
    {
      "name": "fritzbox-bw-current",
      "value": "Down: 0 kB/s | Up: 0 kB/s"
    }
````
