from trello import TrelloClient

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


