# rsa
rsa加密解密

### 生成RSA私钥
```
# openssl genrsa -out rsa_private.key 2048
```

### 生成RSA公钥
```
openssl rsa -in rsa_private.key -pubout -out rsa_public.key
```

```
### 私钥加密 ###
privateEncrypt

### 公钥解密 ###
publicDecrypt
```

```
### 公钥加密 ###
publicEncrypt

### 私钥解密 ###
privateDecrypt
```