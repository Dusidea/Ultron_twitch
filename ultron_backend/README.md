## Creating a custom command

```
php bin/console twitch:collect-viewers
```

=> File created : src/Command/TwitchCollectCommand.php

## Environment variables

a env.local file is needed at the project's root

```
TWITCH_CLIENT_ID=[your Twitch client_id]
TWITCH_ACCESS_TOKEN=[your token]
```

how to get credentials: https://dev.twitch.tv/docs/authentication/getting-tokens-oauth/#client-credentials-grant-flow

## Timezone

Timezone is set in bin > console

## response sort

(Helix API – Get Streams) :
"Streams are sorted by viewer count in descending order by default."
