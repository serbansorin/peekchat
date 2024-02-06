<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\ChatRooms;
use App\Models\Messages;
use App\Models\User;
use App\Models\UserFriends;
use Illuminate\Http\Request;
use Inertia\Inertia;


class MessagesController extends Controller
{
    protected User $user;

    protected $newData = [];

    protected $shareData = [];

    private $sharedOnce = false;
    private $shareAgain = false;

    protected function setOrFailUser()
    {
        $this->user = $this?->user ?? auth()->user();
        if (!$this->user) {
            return redirect()->route('login');
        }
    }


    public function share($data = [])
    {
        if ($this->sharedOnce || !$this->shareAgain) {
            return;
        }

        if (count($data) == 0 && count($this->shareData) == 0) {
            return;
        }

        $this->sharedOnce = true;
        Inertia::share(
            'chat',
            array_merge(
                $this->shareData,
                $data
            )
        );
    }

    public function index()
    {
        $this->setOrFailUser();
        $this->share();

        if (!array_key_exists('chatRooms', $this->newData)) {
            $this->newData['chatRooms'] = ChatRooms::where('user_id', $this->user->id)->get()->toArray();
        }
        if (!array_key_exists('friends', $this->newData)) {
            $this->newData['friends'] = UserFriends::auth()->getFriendsProfiles()->toArray();
        }


        return Inertia::render('Chat', $this->newData);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeMessage(StoreMessageRequest $request)
    {

        if( null == $chat_id = $request->input('chat_id')) {
            return response(['error' => 'chat_id is missing'], 400);
        }
        if (null == $text = $request->input('text')) {
            return response(['error' => 'content is missing'], 400);
        }

        $this->setOrFailUser();
        if ($this->user->id != $request->input('user_id')) {
            return response(['error' => 'user_id is not correct or not this user'], 400);
        }

        $msg = Messages::where('chat_id', $chat_id)->where('user_id', $this->user->id)->first();
        if ($msg) {
            $msg->newChatMessage($text);
        }

        // $message = Messages::create([
        //     'user_id' => $this->user->id,
        //     'friend_id' => $msg->friend_id ?? null,
        //     'text' => $text,
        //     'chat_id' => $chat_id,
        // ]);

        return response()->json(['messages' => Messages::where('chat_id', $chat_id)->get()->toArray()], 200);
    }


    public function sendMessage(StoreMessageRequest $request)
    {
        $this->setOrFailUser();
        $user = $this->user;
        $filename = '';
        $chatUid = $request->input('chat_id');
        $chatRoom = ChatRooms::where('uid', $chatUid)->where('user_id', $user->id)->first();
        $usersList = $chatRoom->users_list;

        if ($request->hasFile('file')) {

            $filename = $request->file('file')->store('public');
        }
        $message = $this->saveMessage($user->id, $user->name, $chatUid, $usersList, $filename);
        if ($message) {
            $messages = Messages::where('chat_id', $chatUid)->get();
        }

        $this->newData = [
            'messages' => $messages,
        ];

        return $this->index();
    }

    public function getMessages(Request $request)
    {
        $this->setOrFailUser();
        $chat_id = $request->get('chat_room');
        $usersList = array_map(function ($user) {
            return intval($user);
        }, $request->get('users_list') ?? []);

        $friendsProfile = User::whereIn('id', $usersList)->get()->toArray();

        if ($chat_id) {
            $chatRoom = ChatRooms::with('messages')->where('id', $chat_id)->orWhere('uid', $chat_id)->first();
            $usersList = $chatRoom ? $chatRoom->users_list : $usersList;
            // return dump($chatRoom);
        }

        if (!isset($chatRoom)) {
            $chatRoom = ChatRooms::chatRoomsByList($usersList)->where('user_id', $this->user->id)->first();
        }

        if ($chatRoom === null) {
            $chatRoom = $this->createNewChatRoom($usersList);
        }

        // $this->shareData = [
        //     'chatRoom' => $chatRoom,
        //     'chatId' => $chatRoom->uid,
        //     'chatRoomId' => $chatRoom->id,
        // ];

        $this->newData = [
            'messages' => $chatRoom->messages ?? [],
            'currentChatRoom' => $chatRoom->toArray(),
            'friendsProfile' => $friendsProfile,
            'chatId' => $chatRoom->uid,
        ];

        return $this->index();

    }


    private function createNewChatRoom($usersList)
    {
        $uid = ChatRooms::generateChatId();
        $desiredChatRoom = [];
        $usersListWithNames = User::whereIn('id', $usersList)->pluck('name', 'id')->toArray();

        foreach ($usersListWithNames as $user => $name) {
            $user = intval($user);

            $chatRoom = ChatRooms::create([
                'user_id' => $user,
                'uid' => $uid,
                'name' => $name . ' Chat',
                'status' => "Hi, I am using PeekChat with my friends",
                'users_list' => $usersList,
                'room_limit' => count($usersList),
                'description' => 'PeekChat Room between ' . count($usersList) . ' friends',
            ]);

            if ($chatRoom !== null && $user === $this->user->id) {
                $desiredChatRoom = $chatRoom;
            }

            $this->saveMessage($user, $name, $uid, $usersList);
        }

        return $desiredChatRoom;
    }

    public function saveMessage(int $user_id, string $name, string $chatRoomUid, array $usersList = [], string $filename = '')
    {
        $messagesData = [
            'user_id' => $user_id,
            'user_name' => $name,
            'chat_id' => $chatRoomUid,
            'text' => 'Hi, I am using PeekChat with my friends',
            'read_at' => now()->format('Y-m-d H:i:s'),
        ];

        if (!empty($filename)) {
            $messagesData['file'] = $filename;
        }

        if (count($usersList) === 2) {
            $messagesData['friend_id'] = (array_values(array_diff($usersList, [$user_id])))[0];
        }

        return Messages::create($messagesData);
    }

}
