#!/usr/bin/env python3

"""
Test server for the at-con registration form.

Runs an http server on port 8000 and serves files from the current directory and sub directory
for GET requests, and for POST requests responds with a JSON object representing a successful
or unsuccessful badge entry depending on the variable below

USED FOR TESTING ONLY
"""

LISTEN_IP = "0.0.0.0"   # IP Address to listen on
PORT = 8000             # Port to listen on
SUCCESS = True          # Respond to POST request with success

import http.server
import socketserver
import logging
import cgi
import json


class ServerHandler(http.server.SimpleHTTPRequestHandler):

    def do_POST(self):
        """
        Handle post requests - just return a success or failure JSON object depending on configuration above
        """
        logging.info(self.headers)
        form = cgi.FieldStorage(
            fp=self.rfile,
            headers=self.headers,
            environ={'REQUEST_METHOD': 'POST',
                     'CONTENT_TYPE': self.headers['Content-Type'],
                     })

        logging.warning("======= POST VALUES =======")
        for item in form.list:
            logging.warning(item)
        logging.warning("\n")

        response_object = {}
        if SUCCESS is True:
            response_object['success'] = True
            response_object['message'] = "Your badge number is: AC123"
        else:
            response_object['success'] = False
            response_object['message'] = "Error: Your information could not be added to the database"

        response = bytes(json.dumps(response_object), "UTF-8")
        self.send_response(200)
        self.send_header("Content-Length", str(len(response)))
        self.end_headers()
        self.wfile.write(response)


if __name__ == "__main__":
    Handler = ServerHandler

    httpd = socketserver.TCPServer((LISTEN_IP, PORT), Handler)

    print("Serving at: http://{0}:{1}".format(LISTEN_IP, PORT))
    httpd.serve_forever()

