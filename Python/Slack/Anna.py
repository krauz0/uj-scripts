from slackclient import SlackClient
import time

def get_user_map(sc):
    resp = sc.api_call("users.list")
    users = {}
    if resp['ok'] and 'members' in resp:
        for member in resp["members"]:
            users[member["id"]] = member["real_name"]

    return users

def get_channel_id_by_name(sc, channel_name):
    resp = sc.api_call("channels.list")

    if resp['ok'] and 'channels' in resp:
        for channel in resp["channels"]:
            if channel["name"] == channel_name:
                return channel["id"]

    return ""

SLACK_TOKEN = "xoxb-359983335414-rya7AhcDnhwwnxEao9G4kUia"

sc = SlackClient(SLACK_TOKEN)

if sc.rtm_connect():
    channel_id = get_channel_id_by_name(sc, "general")
    last_message_id = 0
    while True:
        response = sc.api_call("channels.history", channel=channel_id, count=1, latest="")
        if response['ok']:
            msg = response['messages'][0]
            if msg['ts'] != last_message_id:
                if 'user' in msg:
                    username = get_user_map(sc)[msg["user"]]
                    print(username + ": " + msg["text"])
                    last_message_id = msg['ts']
        time.sleep(5)
else:
    print("Connection failed")

