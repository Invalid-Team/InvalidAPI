# InvalidAPI V1
## Http Get

### LoginAPI(Auth.php)
#### auth.php?user=username&pass=password&hwid=hardwareid&authKey=authKey
> invalid user name -> INVALID_USER

> error pwd -> ERROR_PASSWORD

> hwid err -> INVALID_HWID

> success login -> LOGIN_SUCCESS

> sub outdate -> OUTDATE_SUB

> got banned -> BANNED_USER

### RegisterAPI(register.php)
#### register.php?user=username&pass=password&invitecode=invitecode

> invalid invite code -> INVALID_INVITECODE

> Success register -> SUCCESS_REGISTER

> Failed Register -> ERROR_REGISTER

### InviteAPI(admintool.php)
> this API can Help you generate invitecode.
#### admintool.php?inviter=inviter&authKey=authKey&codefrom=codefrom

> Invalid User -> INVALID_USER

> NOT ADMIN -> NO_ACCESS


