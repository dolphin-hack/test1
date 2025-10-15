import re
import subprocess

#command = "ls -l /data | touch /data/hoge"

cmd1 = "file "
cmd2 = "/data/store/product.jpg `touch vuln`"

command = cmd1 + cmd2
command = re.sub('[\|\'\"\`\<\>\!\@\#\%\^\&\*\\{\}\[\}\]\:\;\?].*', '', command)

print(command)

proc = subprocess.Popen(
    command,
    shell  = True,
    stdin  = subprocess.PIPE,
    stdout = subprocess.PIPE,
    stderr = subprocess.PIPE)

stdout_data, stderr_data = proc.communicate()
print(stdout_data)
#print(stderr_data)
