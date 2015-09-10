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


requirements
- one variable @ pimatic
- variableDevice @ pimatic
- php with curl

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
