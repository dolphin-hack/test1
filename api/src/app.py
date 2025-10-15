import re
import subprocess
import os
from base64 import b64decode
from flask import Flask, jsonify, request

app = Flask(__name__)

## GLOBAL
FILE_DIR = '/data/store/'


def _writeFile(filename, data):
	res = False

	try:
		print(FILE_DIR+filename)
		with open(FILE_DIR + filename, 'wb') as fd:
			fd.write(data)
			res = True

	except Exception as e:
		print("[!] {}".format(str(e)))
		res = False

	return res


def _execCommand(command):
	command = re.sub('[\'\"\`\<\>\!\@\#\%\^\&\*\\{\}\[\}\]\:\;\?].*', '', command)
	print("command: {}".format(command))
	proc = subprocess.Popen(
		command,
		shell  = True,
		stdin  = subprocess.PIPE,
		stdout = subprocess.PIPE,
		stderr = subprocess.PIPE
	)

	stdout, stderr = proc.communicate()

	return stdout, stderr


def _scanFile(filename, data):
	result = {'result':'', 'judge':False, 'error':False}

	if ( not _writeFile(filename, data) ):
		result['result'] = 'File Upload Error'
		return result

	## Phase 1: File Name Check
	if ( not (re.match('^[a-zA-z0-9\-\_\.<>\(\)\[\]]+\.(jpg||png|gif)', filename.lower())) ):
		result['result'] = 'file extention is invalid'
		return result

	## Phase 2: File Sigunature Check
	command = "file " + FILE_DIR + filename
	sig, err = _execCommand(command)

	if ( err.decode('utf-8') != '' ):
		result['error'] = True
		result['result'] = err.decode('utf-8') #for debug

	else:
		if ( re.match('^'+FILE_DIR+'[a-zA-z0-9\-\_\.<>\(\)\[\]]+\.(jpg||png|gif): JPEG image data', sig.decode('utf-8')) ):
			result['result'] = 'jpeg'
			result['judge'] = True

		elif ( re.match('^'+FILE_DIR+'[a-zA-z0-9\-\_\.<>\(\)\[\]]+\.(jpg||png|gif): PNG image data', sig.decode('utf-8')) ):
			result['result'] = 'png'
			result['judge'] = True

		elif ( re.match('^'+FILE_DIR+'[a-zA-z0-9\-\_\.<>\(\)\[\]]+\.(jpg||png|gif): GIF image data', sig.decode('utf-8')) ):
			result['result'] = 'gif'
			result['judge'] = True

		else:
			result['result'] = ''

		## Phase3: Malicious Code Check
		## XSS対策としてHTMLタグっぽい文字列が含まれていたら悪性コードとして判定
		try:
			with open(FILE_DIR + filename, 'rb') as fd:
				data = fd.read()
				## テスト用のシグネチャ
				if ( re.search(rb'sai8233Bm6k1fuwvU6UOZ7nrpIZiiah1', data) ):
					result['judge'] = False
					result['result'] = 'Malicious Code Detect'

		except Exception as e:
			result['result'] = str(e) #for debug

	## File Delete
	#os.remove(FILE_DIR + filename)

	return result


@app.route('/api/inspect', methods=['POST'])
def FileScanner():
	req = request.get_json()
	if ( req is None ):
		return({'status':500, 'error': 'Request Format Error'}), 500

	if ( 'filename' not in req or 'data' not in req ):
		return({'status':500, 'error': 'paramater error'}), 500

	if ( req['filename'] is None or req['filename'] == '' ):
		return({'status':500, 'error':'file name is not found'})

	if ( req['data'] is None or req['data'] == '' ):
		return({'status':500, 'error':'file data is empty or broken.'})


	filename = req['filename']
	
	try:
		data = b64decode(req['data'])
	
	except Exception as e:
		print('[!] {}'.format(e))
		return({'status':500, 'error':'Invalid Format Data'})


	print('[*] filename: {}'.format(filename))
	print('[*] data: {}'.format(data))
	
	result = _scanFile(filename, data)
	print('[*] {}'.format(result))
	if ( not result['judge'] ):
		return({'status':500, 'error':'Invalid file', 'addition':result['result']})

	return ({'status':200, 'result':'success'})


## 疎通確認用
@app.route('/api/status', methods=['GET'])
def status():
	return({'result':'OK'})


if __name__ == '__main__':
	app.run(debug=True,host='0.0.0.0', port=3000)
