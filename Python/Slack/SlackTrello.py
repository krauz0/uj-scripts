from trello import TrelloClient
from slackclient import SlackClient
import time

API_KEY = "b967c4776325bfbc064a72f165db4872"
API_SECRET_KEY = "2a28c29d7fa9cceb3c4eb9254f547c46be8fe471f33345336d90353225c8f42e"
TOKEN = "d01088d6b212047da227f990a7d55cc6496785b458d1a7cbca9e577700b4161d"
TOKEN_SECRET = ""


def setup_trello_board():
    recruitment_board = None
    boards = client.list_boards()
    found = False
    for board in boards:
        if board.name == "Recruitment":
            found = True
            recruitment_board = board

    if not found:
        recruitment_board = client.add_board("Recruitment")
    lists_to_create = ['Prescreening', 'Before interview', 'After interview', 'Finished']

    trello_lists = recruitment_board.all_lists()

    list_map = {}
    for trello_list in trello_lists:
        list_map[trello_list.name] = trello_list

    if lists_to_create[3] not in list_map:
        finished_list = recruitment_board.add_list(lists_to_create[3])
    else:
        finished_list = list_map[lists_to_create[3]]

    if lists_to_create[2] not in list_map:
        after_list = recruitment_board.add_list(lists_to_create[2])
    else:
        after_list = list_map[lists_to_create[2]]


    if lists_to_create[1] not in list_map:
        before_list = recruitment_board.add_list(lists_to_create[1])
    else:
        before_list = list_map[lists_to_create[1]]

    if lists_to_create[0] not in list_map:
        prescreening_list = recruitment_board.add_list(lists_to_create[0])
    else:
        prescreening_list = list_map[lists_to_create[0]]

    return recruitment_board, prescreening_list, before_list, after_list, finished_list

client = TrelloClient(
    api_key=API_KEY,
    api_secret=API_SECRET_KEY,
    token=TOKEN,
    token_secret=TOKEN_SECRET
)

board, prescreening_list, before_list, after_list, finished_list = setup_trello_board()

prescreening_list.add_card("John Kowalski - Data Scientist", "Some description goes here")

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


def reply(sc, cards):
    msg = ""
    for card in cards:
        msg += card.name + "\n"

    sc.api_call("chat.postMessage", channel="#general",
                text=msg, as_user=True)


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
                    if msg["text"] == "list":
                        reply(sc, prescreening_list.list_cards())
                    last_message_id = msg['ts']
        time.sleep(5)
else:
    print("Connection failed")