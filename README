# Installation

1. Clone the directory

```
git clone https://github.com/Kuvvu/php-webhook.git /opt/webhook
```

2. Install Composer Dependencies

```
cd /opt/webhook
composer install
```

3. Create Config File

```
cp config.json.example config.json
```

and fill out the variables accordingly (example)

```
{
  "php-webhook": "cd /opt/webhook && git pull"
}
```

4. Create .env File

```
cp .env.example .env
```

and fill out the variables accordingly (example)

```
SECRET=967B3F47-16D9-4583-B966-F2497968DE61
CONFIG="/opt/webhook/config.json"
```

5. Install WebHook service

```
cd /etc/systemd/system
ln -s /opt/webhook/webhook.service webhook.service
systemctl enable webhook.service
systemctl start webhook.service
```
