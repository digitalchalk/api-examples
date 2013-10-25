<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib prefix="c" uri="http://java.sun.com/jsp/jstl/core" %>
		<div class="span-24">
			<div class="error">
			<p>There was an error processing this request</p>
			<c:if test="${!empty apiResult.error }">
			<br>${apiResult.error }
			</c:if>
			<c:if test="${!empty apiResult.errorDescription }">
			<br>${apiResult.errorDescription }
			</c:if>	
			<c:if test="${!empty apiResult.fieldErrors }">
			<br>Field Errors
			<ul>
			<c:forEach items="${apiResult.fieldErrors }" var="fieldError">
				<li>${fieldError.key } : ${fieldError.value }</li>
			</c:forEach>
			</ul>
			</c:if>				
			</div>
		</div>