using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Threading.Tasks;
using System.Web.Script.Serialization;

namespace dcapicsharp
{
    class DCApi
    {
        String hostname = null; // e.g. yourhost.digitalchalk.com
        String apiKey = null;
        String outProtocol = "https";
        int outPort = 443;
        String contextPath = "dc";

        public DCApi(String hostname, String apiKey)
        {
            this.hostname = hostname;
            this.apiKey = apiKey;

            ServicePointManager.ServerCertificateValidationCallback = new System.Net.Security.RemoteCertificateValidationCallback
                (
                   delegate { return true; }
                );
        }

        public void setOutProtocol(String newProtocol)
        {
            this.outProtocol = newProtocol;
        }

        public void setOutPort(int newPort)
        {
            this.outPort = newPort;
        }


        // =====================================================================
        //
        // Users
        //
        // =====================================================================
        public List<User> getAllUsers()
        {
            List<Object> result = makeApiV5Call("/api/v5/users", "GET", new User().GetType());
            if (result == null)
            {
                return null;
            }
            else
            {
                return result.Cast<User>().ToList();
            }
        }

        public User getUserById(String userId)
        {
            List<Object> result = makeApiV5Call("/api/v5/users/" + userId, "GET", new User().GetType());
            if (result == null)
            {
                return null;
            }
            else
            {
                return (User)result.First();
            }
        }

        public List<User> getUsersWithFilter(Dictionary<String, String> filters)
        {
            List<Object> result = makeApiV5Call("/api/v5/users", "GET", new User().GetType(), filters);
            if (result == null)
            {
                return null;
            }
            else
            {
                return result.Cast<User>().ToList();
            }
        }

        public void createUser(User user)
        {
            JavaScriptSerializer jss = new JavaScriptSerializer();
            String userJson = jss.Serialize(user);
            makeApiV5Call("/api/v5/users", "POST", new User().GetType(), userJson);
            

        }

        public void modifyUser(String userId, Dictionary<String,String> modifiedUserFields) 
        {
            JavaScriptSerializer jss = new JavaScriptSerializer();
            String postData = jss.Serialize(modifiedUserFields);
            makeApiV5Call("/api/v5/users/" + userId, "PUT", new User().GetType(), postData);
        }

        public void deleteUser(String userId)
        {
            makeApiV5Call("/api/v5/users/" + userId, "DELETE", new User().GetType());
        }


        // =====================================================================
        //
        // Offerings
        //
        // =====================================================================
        public List<Offering> getAllOfferings()
        {
            List<Object> result = makeApiV5Call("/api/v5/offerings", "GET", new Offering().GetType());
            if (result == null)
            {
                return null;
            }
            else
            {
                return result.Cast<Offering>().ToList();
            }
        }

        public Offering getOfferingById(String offeringId)
        {
            List<Object> result = makeApiV5Call("/api/v5/offerings/" + offeringId, "GET", new Offering().GetType());
            if (result == null)
            {
                return null;
            }
            else
            {
                return (Offering)result.First();
            }
        }

        private bool needsPort()
        {
            if (this.outProtocol.Equals("http"))
            {
                return (this.outPort != 80);
            }
            else if (this.outProtocol.Equals("https"))
            {
                return (this.outPort != 443);
            }
            else
            {
                return true;
            }
        }

        /**
         * Convenience methods only
         * 
         */
        private List<Object> makeApiV5Call(String pathToCall, String requestMethod, Type resultType)
        {
            // Usually a "GET" all, getById, or DELETE action
            return makeApiV5Call(pathToCall, requestMethod, resultType, (String)null);
        }

        private List<Object> makeApiV5Call(String pathToCall, String requestMethod, Type resultType, Dictionary<String, String> parameters)
        {
            StringBuilder parms = null;
            if (parameters != null)
            {
                bool isFirst = true;
                parms = new StringBuilder();
                foreach (String parm in parameters.Keys)
                {
                    if (!isFirst)
                    {
                        parms.Append("&");
                    }
                    isFirst = false;
                    parms.Append(parm);
                    parms.Append("=");
                    parms.Append(Uri.EscapeDataString(parameters[parm]));
                }
            }
            if (parms != null)
            {
                return makeApiV5Call(pathToCall, requestMethod, resultType, parms.ToString());
            }
            else
            {
                return makeApiV5Call(pathToCall, requestMethod, resultType, (String)null);
            }
        }

        private List<Object> makeApiV5Call(String pathToCall, String requestMethod, Type resultType, String postData)
        {
            StringBuilder sb = new StringBuilder();
            sb.Append(this.outProtocol);
            sb.Append("://");
            sb.Append(this.hostname);
            if (this.needsPort())
            {
                sb.Append(":" + this.outPort);
            }
            sb.Append("/" + this.contextPath);
            sb.Append(pathToCall);          

            if(requestMethod.ToUpper().Equals("GET") && postData != null) {
                if(sb.ToString().Contains("?")) {
                    sb.Append("&");
                } else {
                    sb.Append("?");
                }
                sb.Append(postData);
            }

            Console.WriteLine("About to call URL " + sb.ToString());
            List<Object> result = new List<Object>();

            try
            {
                HttpWebRequest request = (HttpWebRequest)WebRequest.Create(sb.ToString());
                request.Method = requestMethod;
                request.ContentType = "application/json";
                request.Accept = "application/json";
                request.Headers.Add("Authorization", "Bearer " + this.apiKey);

                if(requestMethod.ToUpper().Equals("POST") || requestMethod.ToUpper().Equals("PUT")) {
                    if(postData != null) {
                        byte[] byteArray = Encoding.UTF8.GetBytes(postData);
                        request.ContentLength = byteArray.Length;
                        Stream postStream = request.GetRequestStream();
                        postStream.Write(byteArray, 0, byteArray.Length);
                        postStream.Close();
                    }
                }

                WebResponse response = request.GetResponse();
                Stream dataStream = response.GetResponseStream();
                StreamReader reader = new StreamReader(dataStream);
                String responseText = reader.ReadToEnd();
                response.Close();

                //Console.WriteLine("Response is " + responseText);

                if (responseText != null)
                {
                    JavaScriptSerializer jss = new JavaScriptSerializer();
                    Dictionary<String, Object> resultDict = (Dictionary<String, Object>)jss.DeserializeObject(responseText);
                    if (resultDict != null)
                    {
                        if (resultDict.Keys.Contains("results"))
                        {
                            Object[] objArr = (Object[])resultDict["results"];
                            foreach (Object obj in objArr)
                            {
                                try
                                {
                                    var resultObject = jss.ConvertToType(obj, resultType);
                                    result.Add(resultObject);
                                }
                                catch (Exception jsonEx)
                                {
                                    Console.WriteLine("JSON exception : " + jsonEx.Message);
                                }
                            }

                        }
                        else
                        {
                            var resultObject = jss.Deserialize(responseText, resultType);
                            result.Add(resultObject);
                        }
                    }
                }

                return result;
                
                


            }
            catch (WebException webEx)
            {
                if (webEx.Response != null)
                {
                    using (HttpWebResponse err = (HttpWebResponse)webEx.Response)
                    {
                        Console.WriteLine("The server returned '{0}' with the status code '{1} ({2:d})'.",
                          err.StatusDescription, err.StatusCode, err.StatusCode);
                    }
                }
                else
                {
                    Console.WriteLine(webEx.Message);
                }
            }
            return null;  // default
        }
    }
}
