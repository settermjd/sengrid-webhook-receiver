# SendGrid Webhook Receiver

This is a small project to show how to work with signed SendGrid webhooks in PHP.

## Getting Started

Clone the project and change in to the cloned project directory by running the following commands.

```bash
git clone git@github.com:settermjd/sengrid-webhook-receiver.git
cd sengrid-webhook-receiver
```

Then, copy _.env.template_ to _.env_ and set values for each of the five environment variables.

## Usage

Next, start the application by running the following command.

```bash
composer serve
```

Then, start ngrok, so that the application is publicly available:

```bash
ngrok http 8080
```

Following that, login to your SendGrid dashboard, and under Settings > Mail Settings:

- Enable **Event Webhook** 
  - Leave **Authorization Method** set to `None`
  - Set **HTTP Post URL** to the ngrok Forwarding URL
  - Enable all events to be POSTed to your URL
  - Set **Event Webhook Status** to `ENABLED`
  - Click **Save**
- Enable **Signed Event Webhook Requests**
  - Click Generate Verification Key to generate a public key.
    - Set the generated public key as the value of `SENDGRID_WEBHOOK_PUBLIC_KEY` in _.env_
  - Set **Signed Event Webhook Request Status** to `ENABLED`
  - Click **Close**

After that, send an email by calling the `/email` endpoint, such as by using curl:

```bash
curl http://localhost:8080/email
```

If the webhook's body contents successfully validated, then you'll see "**SendGrid webhook data successfully validated.**" written to _data/log/webhook.log_. 
Otherwise, you'll see "**SendGrid webhook data did not successfully validate.**" written to the file.