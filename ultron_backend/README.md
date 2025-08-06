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

/!\ Security: all .env files should be in gitIgnore to prevent KEY and secret exposure
in .gitingore add _.env_

## Timezone

Timezone is set in bin > console

## response sort

(Helix API – Get Streams) :
"Streams are sorted by viewer count in descending order by default."

by default there's a pagination after 25 results => explicitly request 100 res: " /helix/streams route returns 25 results max by request, except if you mention "first=100".
